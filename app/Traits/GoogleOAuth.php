<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAuthSetting;

trait GoogleOAuth
{

    public function setGoogleoAuthConfig()
    {
        // IMPORTANT: Use SocialAuthSetting credentials first, as tokens were created with these
        // This ensures tokens work with the same client ID/secret that created them
        $socialAuthSetting = SocialAuthSetting::first();
        
        // Try to get client ID from SocialAuthSetting first, then global_setting, then env
        $clientId = null;
        if ($socialAuthSetting && !empty($socialAuthSetting->google_client_id)) {
            $clientId = $socialAuthSetting->google_client_id;
        }
        
        if (empty($clientId)) {
            $setting = global_setting();
            $clientId = $setting->google_client_id ?? env('GOOGLE_CLIENT_ID');
        }
        
        Config::set('services.google.client_id', $clientId);

        // Set client secret - try SocialAuthSetting first (same as tokens), then global_setting, then env
        $clientSecret = env('GOOGLE_CLIENT_SECRET'); // Default to env
        
        // First try SocialAuthSetting (this is what was used to create the tokens)
        // Note: google_secret_id is encrypted, so accessing it may throw DecryptException
        try {
            if ($socialAuthSetting && !empty($socialAuthSetting->google_secret_id)) {
                $clientSecret = $socialAuthSetting->google_secret_id; // This may throw DecryptException
                Log::info('Google OAuth: Using credentials from SocialAuthSetting');
            }
        } catch (DecryptException $e) {
            // If decryption fails, try global_setting or env
            Log::warning('Google OAuth: Failed to decrypt google_secret_id from SocialAuthSetting, trying global_setting. Error: ' . $e->getMessage());
            
            try {
                $setting = global_setting();
                if ($setting && !empty($setting->google_client_secret)) {
                    $clientSecret = $setting->google_client_secret; // This may also throw DecryptException
                }
            } catch (DecryptException $e2) {
                // If global_setting also fails, use env
                Log::warning('Google OAuth: Failed to decrypt google_client_secret from global_setting, using environment variable. Error: ' . $e2->getMessage());
                $clientSecret = env('GOOGLE_CLIENT_SECRET');
            } catch (\Exception $e2) {
                Log::warning('Google OAuth: Error accessing google_client_secret from global_setting, using environment variable. Error: ' . $e2->getMessage());
                $clientSecret = env('GOOGLE_CLIENT_SECRET');
            }
        } catch (\Exception $e) {
            // Catch any other exceptions and try global_setting or env
            Log::warning('Google OAuth: Error accessing google_secret_id from SocialAuthSetting, trying global_setting. Error: ' . $e->getMessage());
            
            try {
                $setting = global_setting();
                if ($setting && !empty($setting->google_client_secret)) {
                    $clientSecret = $setting->google_client_secret;
                }
            } catch (\Exception $e2) {
                $clientSecret = env('GOOGLE_CLIENT_SECRET');
            }
        }
        
        Config::set('services.google.client_secret', $clientSecret);
        Config::set('services.google.redirect_uri', route('googleAuth'));
    }

}
