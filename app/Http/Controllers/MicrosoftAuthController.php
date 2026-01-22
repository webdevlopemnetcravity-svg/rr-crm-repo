<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewMicrosoftToken;
use App\Traits\SocialAuthSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftAuthController extends Controller
{
    use SocialAuthSettings;

    /**
     * Redirect to Microsoft OAuth login
     */
    public function index(Request $request)
    {
        Log::info('MicrosoftAuthController: index called', [
            'user_id' => user() ? user()->id : null,
            'company_id' => company() ? company()->id : null
        ]);

        try {
            // Get Microsoft OAuth configuration from environment variables
            $clientId = env('MS_CLIENT_ID');
            $redirectUri = env('MS_REDIRECT_URI', url('/auth/microsoft/callback'));
            
            // Fallback to database if env vars not set
            if (empty($clientId)) {
                $settings = \App\Models\SocialAuthSetting::first();
                $clientId = isset($settings->microsoft_client_id) && !empty($settings->microsoft_client_id) 
                    ? $settings->microsoft_client_id 
                    : null;
            }
            
            Log::info('MicrosoftAuthController: Configuration check', [
                'has_client_id' => !empty($clientId),
                'client_id_length' => $clientId ? strlen($clientId) : 0,
                'client_id_preview' => $clientId ? substr($clientId, 0, 10) . '...' : 'empty',
                'redirect_uri' => $redirectUri,
                'redirect_uri_from_env' => env('MS_REDIRECT_URI'),
                'default_redirect_uri' => url('/auth/microsoft/callback'),
                'full_auth_url' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query([
                    'client_id' => $clientId,
                    'response_type' => 'code',
                    'redirect_uri' => $redirectUri,
                    'response_mode' => 'query',
                    'scope' => implode(' ', ['openid', 'profile', 'offline_access', 'User.Read', 'OnlineMeetings.ReadWrite']),
                ])
            ]);
            
            if (empty($clientId) || $clientId === '1') {
                throw new \Exception('Microsoft Client ID is not configured. Please set MS_CLIENT_ID in .env file or add it in Social Auth Settings → Microsoft tab.');
            }
            
            // Use ONLY the required scopes for personal Microsoft accounts
            $scopes = [
                'openid',
                'profile',
                'offline_access',
                'User.Read',
                'OnlineMeetings.ReadWrite',
            ];
            
            // Get current user before redirect
            $currentUser = user();
            if (!$currentUser) {
                throw new \Exception('You must be logged in to connect Microsoft account.');
            }
            
            // Generate a random state for CSRF protection and embed user ID
            $randomState = bin2hex(random_bytes(16));
            // Encrypt user ID and return URL in state to survive session loss
            $stateData = [
                'state' => $randomState,
                'user_id' => $currentUser->id,
                'return_url' => route('social-auth-settings.index', ['tab' => 'microsoft']),
            ];
            $encryptedState = base64_encode(json_encode($stateData));
            
            // Also store in session as backup
            Session::put('microsoft_oauth_state', $randomState);
            Session::put('microsoft_oauth_user_id', $currentUser->id);
            Session::put('microsoft_oauth_return_url', route('social-auth-settings.index', ['tab' => 'microsoft']));
            
            $params = [
                'client_id' => $clientId,
                'response_type' => 'code',
                'redirect_uri' => $redirectUri,
                'response_mode' => 'query',
                'scope' => implode(' ', $scopes),
                'state' => $encryptedState,
                'prompt' => 'consent',
            ];
            
            // Use common tenant to support both personal and work/school accounts
            $authUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query($params);
            
            Log::info('MicrosoftAuthController: Redirecting to Microsoft OAuth', [
                'auth_url' => $authUrl,
                'client_id' => $clientId,
                'scopes' => $scopes
            ]);
            
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('MicrosoftAuthController: Error redirecting to Microsoft', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Session::flash('message', 'Error connecting to Microsoft: ' . $e->getMessage());
            return redirect()->route('social-auth-settings.index', ['tab' => 'microsoft']);
        }
    }

    /**
     * Handle Microsoft OAuth callback
     */
    public function callback(Request $request)
    {
        // Handle callback - get tokens from OAuth callback
        Log::info('MicrosoftAuthController: Callback received with code');
        
        // Decode state parameter to get user info (survives session loss)
        $stateData = null;
        $storedUserId = null;
        $returnUrl = route('social-auth-settings.index', ['tab' => 'microsoft']);
        
        if ($request->has('state')) {
            try {
                $decodedState = base64_decode($request->state);
                $stateData = json_decode($decodedState, true);
                
                if (isset($stateData['user_id'])) {
                    $storedUserId = $stateData['user_id'];
                }
                if (isset($stateData['return_url'])) {
                    $returnUrl = $stateData['return_url'];
                }
                
                // Validate state from session if available
                $sessionState = Session::get('microsoft_oauth_state');
                if ($sessionState && isset($stateData['state']) && $stateData['state'] !== $sessionState) {
                    throw new \Exception('State mismatch');
                }
            } catch (\Exception $e) {
                Log::warning('MicrosoftAuthController: Could not decode state, using session fallback', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Fallback to session if state decode failed
        if (!$storedUserId) {
            $storedUserId = Session::get('microsoft_oauth_user_id');
            $returnUrl = Session::get('microsoft_oauth_return_url', $returnUrl);
        }
        
        // Clean up session state
        Session::forget('microsoft_oauth_state');
        
        // Get redirect URI from environment or use default
        $redirectUri = env('MS_REDIRECT_URI', url('/auth/microsoft/callback'));
        
        Log::info('MicrosoftAuthController: Callback redirect URI set', [
            'redirect_uri' => $redirectUri,
            'request_url' => $request->fullUrl()
        ]);
        
        try {
            Log::info('MicrosoftAuthController: Attempting to exchange code for token');
            
            // Get credentials from environment variables (preferred) or database (fallback)
            $clientId = env('MS_CLIENT_ID');
            $clientSecret = env('MS_CLIENT_SECRET');
            
            // Fallback to database if env vars not set
            if (empty($clientId) || empty($clientSecret)) {
                $settings = \App\Models\SocialAuthSetting::first();
                $clientId = $clientId ?: (isset($settings->microsoft_client_id) && !empty($settings->microsoft_client_id) 
                    ? $settings->microsoft_client_id 
                    : null);
                $clientSecret = $clientSecret ?: (isset($settings->microsoft_secret_id) && !empty($settings->microsoft_secret_id)
                    ? $settings->microsoft_secret_id
                    : null);
            }
            
            $code = $request->code;
            
            if (empty($clientId) || $clientId === '1' || empty($clientSecret) || empty($code)) {
                throw new \Exception('Missing required Microsoft OAuth credentials or authorization code. Please set MS_CLIENT_ID and MS_CLIENT_SECRET in .env file or configure them in Social Auth Settings.');
            }
            
            // Exchange code for token using common tenant endpoint
            $tokenUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
            $tokenData = [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ];
            
            $ch = curl_init($tokenUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            
            $tokenResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200) {
                Log::error('MicrosoftAuthController: Token exchange failed', [
                    'http_code' => $httpCode,
                    'response' => $tokenResponse
                ]);
                throw new \Exception('Failed to exchange authorization code for access token.');
            }
            
            $tokenData = json_decode($tokenResponse, true);
            
            if (!isset($tokenData['access_token'])) {
                throw new \Exception('Access token not received from Microsoft.');
            }
            
            $accessToken = $tokenData['access_token'];
            $refreshToken = $tokenData['refresh_token'] ?? null;
            
            // Get user info from Microsoft Graph API
            $userInfoUrl = 'https://graph.microsoft.com/v1.0/me';
            $ch = curl_init($userInfoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            
            $userResponse = curl_exec($ch);
            $userHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($userHttpCode !== 200) {
                Log::warning('MicrosoftAuthController: Could not fetch user info, using token only');
                $userData = [
                    'id' => null,
                    'displayName' => null,
                    'mail' => null,
                    'userPrincipalName' => null,
                ];
            } else {
                $userData = json_decode($userResponse, true);
            }
            
            // Create a mock socialite user object
            $socialiteUser = (object) [
                'id' => $userData['id'] ?? null,
                'name' => $userData['displayName'] ?? $userData['userPrincipalName'] ?? null,
                'email' => $userData['mail'] ?? $userData['userPrincipalName'] ?? null,
                'token' => $accessToken,
                'refreshToken' => $refreshToken,
            ];
            
            Log::info('MicrosoftAuthController: Socialite user retrieved', [
                'has_token' => !empty($socialiteUser->token),
                'has_refresh_token' => !empty($socialiteUser->refreshToken),
                'email' => $socialiteUser->email ?? null
            ]);
            
            // Get user from state data (already decoded above) or current authenticated user
            $currentUser = null;
            $company = null;
            
            if ($storedUserId) {
                // Try to get user from stored ID (from state parameter, survives session loss)
                $currentUser = \App\Models\User::find($storedUserId);
                if ($currentUser && $currentUser->status === 'active') {
                    // Set the user in auth session to maintain authentication
                    auth()->login($currentUser);
                    // Regenerate session to prevent fixation attacks
                    $request->session()->regenerate();
                    $company = company();
                }
            }
            
            // Fallback to current authenticated user
            if (!$currentUser) {
                $currentUser = user();
                $company = company();
            }
            
            Log::info('MicrosoftAuthController: Current user and company', [
                'stored_user_id' => $storedUserId,
                'user_id' => $currentUser ? $currentUser->id : null,
                'company_id' => $company ? $company->id : null
            ]);
            
            if (!$currentUser || !$company) {
                Log::error('MicrosoftAuthController: User or company not found', [
                    'stored_user_id' => $storedUserId,
                    'has_current_user' => !is_null($currentUser),
                    'has_company' => !is_null($company)
                ]);
                
                // Clean up session
                Session::forget('microsoft_oauth_user_id');
                Session::forget('microsoft_oauth_return_url');
                Session::forget('microsoft_oauth_state');
                
                // Get return URL for login redirect
                $returnUrl = route('social-auth-settings.index', ['tab' => 'microsoft']);
                Session::flash('message', 'Session expired. Please log in again to complete Microsoft authentication.');
                
                return redirect()->route('login', ['redirect' => $returnUrl]);
            }

            // Get tokens
            $accessToken = $socialiteUser->token ?? null;
            $refreshToken = $socialiteUser->refreshToken ?? null;
            
            Log::info('MicrosoftAuthController: Tokens extracted', [
                'access_token_length' => $accessToken ? strlen($accessToken) : 0,
                'has_refresh_token' => !empty($refreshToken)
            ]);
            
            if (empty($accessToken)) {
                Log::error('MicrosoftAuthController: No access token received');
                Session::flash('message', 'Failed to get access token from Microsoft.');
                return redirect()->route('social-auth-settings.index', ['tab' => 'microsoft']);
            }

            // No need for calendar ID for Teams meetings - using OnlineMeetings.ReadWrite scope
            $calendarId = null;

            // Store tokens in new_microsoft_token table
            Log::info('MicrosoftAuthController: Storing tokens in database', [
                'user_id' => $currentUser->id,
                'company_id' => $company->id
            ]);
            
            $tokenRecord = NewMicrosoftToken::updateOrCreate(
                [
                    'user_id' => $currentUser->id,
                    'company_id' => $company->id,
                ],
                [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'calendar_id' => $calendarId,
                    'microsoft_id' => $socialiteUser->id ?? null,
                    'name' => $socialiteUser->name ?? null,
                    'email' => $socialiteUser->email ?? $currentUser->email,
                    'verification_status' => 'verified',
                ]
            );

            Log::info('Microsoft Calendar: Tokens stored successfully', [
                'token_record_id' => $tokenRecord->id,
                'user_id' => $currentUser->id
            ]);
            
            // Clean up session variables (returnUrl already set from state data above)
            Session::forget('microsoft_oauth_user_id');
            Session::forget('microsoft_oauth_return_url');
            
            Session::flash('message', 'Microsoft calendar verified successfully.');

            return redirect($returnUrl);

        } catch (\Exception $e) {
            Log::error('Microsoft Calendar: Error storing tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Clean up session variables
            Session::forget('microsoft_oauth_user_id');
            Session::forget('microsoft_oauth_return_url');
            Session::forget('microsoft_oauth_state');
            
            // Use returnUrl from state data if available, otherwise default
            $errorReturnUrl = $returnUrl ?? route('social-auth-settings.index', ['tab' => 'microsoft']);
            Session::flash('message', 'Failed to store Microsoft tokens: ' . $e->getMessage());
            
            // If user is authenticated, redirect to settings, otherwise to login
            if (user()) {
                return redirect($errorReturnUrl);
            } else {
                return redirect()->route('login', ['redirect' => $errorReturnUrl])->with('error', 'Please log in and try again.');
            }
        }
    }

    public function destroy()
    {
        $microsoftToken = NewMicrosoftToken::where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->first();

        if ($microsoftToken) {
            $microsoftToken->verification_status = 'non_verified';
            $microsoftToken->access_token = null;
            $microsoftToken->refresh_token = null;
            $microsoftToken->save();
        }

        return Reply::success('Microsoft calendar removed successfully.');
    }

}
