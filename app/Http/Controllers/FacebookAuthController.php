<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewFacebookToken;
use App\Traits\SocialAuthSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    use SocialAuthSettings;

    public function index(Request $request)
    {
        Log::info('FacebookAuthController: index called', [
            'has_code' => !empty($request->code),
            'user_id' => user() ? user()->id : null,
            'company_id' => company() ? company()->id : null
        ]);

        // If no code, redirect to Facebook OAuth
        if (!$request->code) {
            Log::info('FacebookAuthController: No code, redirecting to Facebook OAuth');
            
            // Set redirect URI FIRST before calling setSocailAuthConfigs
            // This ensures it's not overridden
            $redirectUri = route('facebookAuth');
            Config::set('services.facebook.redirect', $redirectUri);
            
            // Now set the social auth configs (this will set client_id and secret)
            $this->setSocailAuthConfigs();
            
            // Override redirect URI again after setSocailAuthConfigs (it might have changed it)
            Config::set('services.facebook.redirect', $redirectUri);
            
            Log::info('FacebookAuthController: Setting redirect URI', [
                'redirect_uri' => $redirectUri,
                'route_name' => 'facebookAuth',
                'config_redirect' => config('services.facebook.redirect')
            ]);
            
            try {
                // Set Graph API version to v18.0 and use setScopes to replace all scopes (prevents default email scope)
                $redirect = Socialite::driver('facebook')
                    ->usingGraphVersion('v18.0')
                    ->setScopes(['pages_show_list', 'pages_read_engagement', 'leads_retrieval'])
                    ->redirect();
                
                Log::info('FacebookAuthController: Redirecting to Facebook OAuth with scopes: pages_show_list, pages_read_engagement, leads_retrieval');
                return $redirect;
            } catch (\Exception $e) {
                Log::error('FacebookAuthController: Error redirecting to Facebook', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                Session::flash('message', 'Error connecting to Facebook: ' . $e->getMessage());
                return redirect()->route('social-auth-settings.index');
            }
        }

        // Handle callback - get tokens from Socialite
        Log::info('FacebookAuthController: Callback received with code');
        
        // Set redirect URI FIRST, before calling setSocailAuthConfigs
        // This MUST match exactly what was sent in the initial authorization request
        $redirectUri = route('facebookAuth');
        
        // Set the social auth configs first (for client_id and secret)
        $this->setSocailAuthConfigs();
        
        // Override redirect URI to match the initial request
        // This is critical - it must match exactly
        Config::set('services.facebook.redirect', $redirectUri);
        
        Log::info('FacebookAuthController: Callback redirect URI set', [
            'redirect_uri' => $redirectUri,
            'config_redirect' => config('services.facebook.redirect'),
            'request_url' => $request->fullUrl()
        ]);
        
        try {
            Log::info('FacebookAuthController: Attempting to get user from Socialite');
            
            // Use redirectUri() method to explicitly set the redirect URI for token exchange
            // Set Graph API version to v18.0 to match the authorization request
            $socialiteUser = Socialite::driver('facebook')
                ->usingGraphVersion('v18.0')
                ->redirectUrl($redirectUri)
                ->stateless()
                ->user();
            
            $currentUser = user();
            $company = company();
            
            Log::info('FacebookAuthController: Current user and company', [
                'user_id' => $currentUser ? $currentUser->id : null,
                'company_id' => $company ? $company->id : null
            ]);
            
            if (!$currentUser || !$company) {
                Log::error('FacebookAuthController: User or company not found');
                Session::flash('message', 'User or company not found.');
                return redirect()->route('social-auth-settings.index');
            }

            // Step 1: Get short-lived access token from Socialite (Meta provides 1-2 hour tokens)
            // Note: Meta does NOT provide refresh tokens - we must exchange for long-lived token
            $shortLivedToken = $socialiteUser->token ?? null;
            
            if (empty($shortLivedToken)) {
                Log::error('FacebookAuthController: No short-lived access token received');
                Session::flash('message', 'Failed to get access token from Facebook.');
                return redirect()->route('social-auth-settings.index');
            }

            // Log short-lived token (masked for security)
            $shortTokenMasked = $this->maskToken($shortLivedToken);
            Log::info('FacebookAuthController: Short-lived token received', [
                'token_masked' => $shortTokenMasked,
                'token_length' => strlen($shortLivedToken)
            ]);

            // Step 2: Exchange short-lived token for long-lived token (~60 days)
            // Meta uses fb_exchange_token grant type for this exchange
            $clientId = config('services.facebook.client_id');
            $clientSecret = config('services.facebook.client_secret');
            
            if (empty($clientId) || empty($clientSecret)) {
                Log::error('FacebookAuthController: Missing Facebook credentials');
                Session::flash('message', 'Facebook credentials not configured.');
                return redirect()->route('social-auth-settings.index');
            }

            // Exchange token endpoint: https://graph.facebook.com/v18.0/oauth/access_token
            $exchangeUrl = 'https://graph.facebook.com/v18.0/oauth/access_token';
            $exchangeData = [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'fb_exchange_token' => $shortLivedToken,
            ];

            Log::info('FacebookAuthController: Exchanging short-lived token for long-lived token');
            
            $ch = curl_init($exchangeUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($exchangeData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            
            $exchangeResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error('FacebookAuthController: Token exchange failed', [
                    'http_code' => $httpCode,
                    'response' => $exchangeResponse
                ]);
                Session::flash('message', 'Failed to exchange token for long-lived access token.');
                return redirect()->route('social-auth-settings.index');
            }

            $exchangeResult = json_decode($exchangeResponse, true);
            
            if (!isset($exchangeResult['access_token'])) {
                Log::error('FacebookAuthController: Long-lived access token not received', [
                    'response' => $exchangeResult
                ]);
                Session::flash('message', 'Failed to get long-lived access token from Facebook.');
                return redirect()->route('social-auth-settings.index');
            }

            // Get long-lived token and expiration info
            $longLivedToken = $exchangeResult['access_token'];
            $expiresIn = $exchangeResult['expires_in'] ?? 5184000; // Default to 60 days in seconds if not provided
            
            // Calculate expiration timestamp
            $expiresAt = now()->addSeconds($expiresIn);

            // Log long-lived token info (masked for security)
            $longTokenMasked = $this->maskToken($longLivedToken);
            Log::info('FacebookAuthController: Long-lived token received', [
                'token_masked' => $longTokenMasked,
                'token_length' => strlen($longLivedToken),
                'expires_in' => $expiresIn,
                'expires_in_days' => round($expiresIn / 86400, 2),
                'expires_at' => $expiresAt->toDateTimeString(),
                'expires_at_timestamp' => $expiresAt->timestamp
            ]);

            // Step 3: Store long-lived token in database
            // Note: Meta does NOT provide refresh tokens - only long-lived access tokens
            // The refresh_token field is kept for database compatibility but will always be null
            Log::info('FacebookAuthController: Storing long-lived token in database', [
                'user_id' => $currentUser->id,
                'company_id' => $company->id,
                'expires_at' => $expiresAt->toDateTimeString()
            ]);
            
            $tokenRecord = NewFacebookToken::updateOrCreate(
                [
                    'user_id' => $currentUser->id,
                    'company_id' => $company->id,
                ],
                [
                    'access_token' => $longLivedToken, // Store long-lived token
                    'refresh_token' => null, // Meta does not provide refresh tokens
                    'expires_at' => $expiresAt,
                    'facebook_id' => $socialiteUser->id ?? null,
                    'name' => $socialiteUser->name ?? null,
                    'email' => $socialiteUser->email ?? $currentUser->email,
                    'verification_status' => 'verified',
                ]
            );

            Log::info('Facebook: Long-lived token stored successfully', [
                'token_record_id' => $tokenRecord->id,
                'user_id' => $currentUser->id,
                'expires_at' => $expiresAt->toDateTimeString()
            ]);
            
            Session::flash('message', 'Facebook tokens verified and stored successfully.');

            return redirect()->route('social-auth-settings.index');

        } catch (\Exception $e) {
            Log::error('Facebook: Error storing tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Session::flash('message', 'Failed to store Facebook tokens: ' . $e->getMessage());
            return redirect()->route('social-auth-settings.index');
        }
    }

    /**
     * Mask token for secure logging (shows first 4 and last 4 characters)
     */
    private function maskToken($token)
    {
        if (empty($token) || strlen($token) < 8) {
            return '****';
        }
        return substr($token, 0, 4) . str_repeat('*', strlen($token) - 8) . substr($token, -4);
    }

    public function destroy()
    {
        $facebookToken = NewFacebookToken::where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->first();

        if ($facebookToken) {
            $facebookToken->verification_status = 'non_verified';
            $facebookToken->access_token = null;
            $facebookToken->refresh_token = null; // Meta doesn't provide refresh tokens, but clear for consistency
            $facebookToken->expires_at = null;
            $facebookToken->save();
        }

        return Reply::success('Facebook tokens removed successfully.');
    }

}
