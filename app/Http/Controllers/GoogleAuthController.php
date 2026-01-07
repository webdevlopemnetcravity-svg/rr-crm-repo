<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewGoogleToken;
use App\Services\Google;
use App\Traits\SocialAuthSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    use SocialAuthSettings;

    public function index(Request $request)
    {
        Log::info('GoogleAuthController: index called', [
            'has_code' => !empty($request->code),
            'user_id' => user() ? user()->id : null,
            'company_id' => company() ? company()->id : null
        ]);

        // If no code, redirect to Google OAuth
        if (!$request->code) {
            Log::info('GoogleAuthController: No code, redirecting to Google OAuth');
            
            // Set redirect URI FIRST before calling setSocailAuthConfigs
            // This ensures it's not overridden
            $redirectUri = route('googleAuth');
            Config::set('services.google.redirect', $redirectUri);
            
            // Now set the social auth configs (this will set client_id and secret)
            $this->setSocailAuthConfigs();
            
            // Override redirect URI again after setSocailAuthConfigs (it might have changed it)
            Config::set('services.google.redirect', $redirectUri);
            
            Log::info('GoogleAuthController: Setting redirect URI', [
                'redirect_uri' => $redirectUri,
                'route_name' => 'googleAuth',
                'config_redirect' => config('services.google.redirect')
            ]);
            
            try {
                $redirect = Socialite::driver('google')
                    ->scopes([
                        'https://www.googleapis.com/auth/userinfo.email',
                        'https://www.googleapis.com/auth/userinfo.profile',
                        'https://www.googleapis.com/auth/calendar',
                    ])
                    ->with(['access_type' => 'offline', 'prompt' => 'consent'])
                    ->redirect();
                
                Log::info('GoogleAuthController: Redirecting to Google OAuth');
                return $redirect;
            } catch (\Exception $e) {
                Log::error('GoogleAuthController: Error redirecting to Google', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                Session::flash('message', 'Error connecting to Google: ' . $e->getMessage());
                return redirect()->route('social-auth-settings.index');
            }
        }

        // Handle callback - get tokens from Socialite
        Log::info('GoogleAuthController: Callback received with code');
        
        // Set redirect URI FIRST, before calling setSocailAuthConfigs
        // This MUST match exactly what was sent in the initial authorization request
        $redirectUri = route('googleAuth');
        
        // Set the social auth configs first (for client_id and secret)
        $this->setSocailAuthConfigs();
        
        // Override redirect URI to match the initial request
        // This is critical - it must match exactly
        Config::set('services.google.redirect', $redirectUri);
        
        Log::info('GoogleAuthController: Callback redirect URI set', [
            'redirect_uri' => $redirectUri,
            'config_redirect' => config('services.google.redirect'),
            'request_url' => $request->fullUrl()
        ]);
        
        try {
            Log::info('GoogleAuthController: Attempting to get user from Socialite');
            
            // Use redirectUri() method to explicitly set the redirect URI for token exchange
            $socialiteUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->stateless()
                ->user();
            
            Log::info('GoogleAuthController: Socialite user retrieved', [
                'has_token' => !empty($socialiteUser->token),
                'has_refresh_token' => !empty($socialiteUser->refreshToken),
                'email' => $socialiteUser->email ?? null
            ]);
            
            $currentUser = user();
            $company = company();
            
            Log::info('GoogleAuthController: Current user and company', [
                'user_id' => $currentUser ? $currentUser->id : null,
                'company_id' => $company ? $company->id : null
            ]);
            
            if (!$currentUser || !$company) {
                Log::error('GoogleAuthController: User or company not found');
                Session::flash('message', 'User or company not found.');
                return redirect()->route('social-auth-settings.index');
            }

            // Get tokens
            $accessToken = $socialiteUser->token ?? null;
            $refreshToken = $socialiteUser->refreshToken ?? null;
            
            Log::info('GoogleAuthController: Tokens extracted', [
                'access_token_length' => $accessToken ? strlen($accessToken) : 0,
                'has_refresh_token' => !empty($refreshToken)
            ]);
            
            if (empty($accessToken)) {
                Log::error('GoogleAuthController: No access token received');
                Session::flash('message', 'Failed to get access token from Google.');
                return redirect()->route('social-auth-settings.index');
            }

            // Get calendar ID
            $calendarId = 'primary';
            try {
                Log::info('GoogleAuthController: Attempting to get calendar ID');
                $googleService = new Google();
                $googleClient = $googleService->getClient();
                $googleClient->setAccessToken($accessToken);
                $calendarService = $googleService->service('Calendar');
                $calendarList = $calendarService->calendarList->listCalendarList();
                if ($calendarList->getItems() && count($calendarList->getItems()) > 0) {
                    $calendarId = $calendarList->getItems()[0]->getId();
                }
                Log::info('GoogleAuthController: Calendar ID retrieved', ['calendar_id' => $calendarId]);
            } catch (\Exception $e) {
                Log::warning('Google Calendar: Could not get calendar ID, using primary. Error: ' . $e->getMessage());
                $calendarId = 'primary';
            }

            // Store tokens in new_google_token table
            Log::info('GoogleAuthController: Storing tokens in database', [
                'user_id' => $currentUser->id,
                'company_id' => $company->id
            ]);
            
            $tokenRecord = NewGoogleToken::updateOrCreate(
                [
                    'user_id' => $currentUser->id,
                    'company_id' => $company->id,
                ],
                [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'calendar_id' => $calendarId,
                    'google_id' => $socialiteUser->id ?? null,
                    'name' => $socialiteUser->name ?? null,
                    'email' => $socialiteUser->email ?? $currentUser->email,
                    'verification_status' => 'verified',
                ]
            );

            Log::info('Google Calendar: Tokens stored successfully', [
                'token_record_id' => $tokenRecord->id,
                'user_id' => $currentUser->id
            ]);
            
            Session::flash('message', __('messages.googleCalendar.verifiedSuccess'));

            return redirect()->route('social-auth-settings.index');

        } catch (\Exception $e) {
            Log::error('Google Calendar: Error storing tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Session::flash('message', 'Failed to store Google tokens: ' . $e->getMessage());
            return redirect()->route('social-auth-settings.index');
        }
    }

    public function destroy()
    {
        $googleAccount = \company();
        $googleAccount->google_calendar_verification_status = 'non_verified';
        $googleAccount->google_id = '';
        $googleAccount->name = '';
        $googleAccount->token = '';
        $googleAccount->save();

        session()->forget('company_setting');
        session()->forget('company');

        return Reply::success(__('messages.googleCalendar.removedSuccess'));
    }

}
