<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\Reply;
use App\Models\Social;
use App\Models\NewGoogleToken;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Events\TwoFactorCodeEvent;
use App\Traits\SocialAuthSettings;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use \Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    use AppBoot, SocialAuthSettings;

    protected $redirectTo = 'account/dashboard';

    public function checkEmail(LoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->select('id')
            ->where('status', 'active')
            ->where('login', 'enable')
            ->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                Fortify::username() => __('messages.invalidOrInactiveAccount'),
            ]);
        }

        return response([
            'status' => 'success'
        ]);
    }

    public function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($request->code == $user->two_factor_code) {

            // Reset codes and expire_at after verification
            $user->resetTwoFactorCode();

            // Attempt login
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        // Reset codes and expire_at after failure
        $user->resetTwoFactorCode();

        return redirect()->back()->withErrors(['two_factor_code' => __('messages.codeNotMatch')]);
    }

    public function resendCode(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->generateTwoFactorCode();
        event(new TwoFactorCodeEvent($user));

        return Reply::success(__('messages.codeSent'));
    }

    public function redirect($provider)
    {
        $this->setSocailAuthConfigs();

        // For Google, request calendar scopes and offline access for refresh token
        if ($provider === 'google') {
            return Socialite::driver($provider)
                ->scopes([
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/userinfo.profile',
                    'https://www.googleapis.com/auth/calendar',
                ])
                ->with(['access_type' => 'offline', 'prompt' => 'consent'])
                ->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $this->setSocailAuthConfigs();

        try {
            try {
                if ($provider != 'twitter' && $provider != 'linkedin') {
                    $data = Socialite::driver($provider)->stateless()->user(); /* @phpstan-ignore-line */
                }
                elseif ($provider == 'twitter') {
                    $data = Socialite::driver('twitter-oauth-2')->user(); /* @phpstan-ignore-line */
                }
                elseif ($provider == 'linkedin') {
                    $data = Socialite::driver('linkedin-openid')->user(); /* @phpstan-ignore-line */
                }
                else {
                    $data = Socialite::driver($provider)->user();
                }
            } catch (Exception $e) {

                return redirect()->route('login')->with(['message' => $e->getMessage()]);
            }

            if ($provider == 'twitter') {
                $user = User::where(['twitter_id' => $data->id])->first();
            }
            else {
                $user = User::where(['email' => $data->email])->first();
            }


            if (!$user) {
                return redirect()->route('login')->with(['message' => __('messages.unAuthorisedUser')]);
            }

            if ($user->status === 'deactive') {
                return redirect()->route('login')->with(['message' => __('auth.failedBlocked')]);
            }

            if ($user->login === 'disable') {
                return redirect()->route('login')->with(['message' => __('auth.failedLoginDisabled')]);
            }

            // User found
            DB::beginTransaction();

            Social::updateOrCreate(['user_id' => $user->id], [
                'social_id' => $data->id,
                'social_service' => $provider,
            ]);

            // If Google login, store Google tokens for all users
            if ($provider === 'google') {
                try {
                    $this->storeGoogleTokens($user, $data);
                } catch (Exception $e) {
                    Log::error('Google Calendar: Failed to store tokens: ' . $e->getMessage());
                    // Don't fail login if token storage fails
                }
            }

            DB::commit();

            Auth::login($user, true);

            return redirect()->intended($this->redirectPath());

        } catch (Exception $e) {

            return redirect()->route('login')->with(['message' => $e->getMessage()]);
        }
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/login';
    }

    public function username()
    {
        return 'email';
    }

    /**
     * Store Google tokens for admin users
     */
    private function storeGoogleTokens(User $user, $socialiteUser)
    {
        // Get company from user
        $company = $user->company;
        if (!$company) {
            Log::warning('Google Calendar: No company found for user ' . $user->id);
            return;
        }

        // Get tokens from Socialite user
        $accessToken = $socialiteUser->token ?? null;
        $refreshToken = $socialiteUser->refreshToken ?? null;

        if (empty($accessToken)) {
            Log::warning('Google Calendar: No access token received for user ' . $user->id);
            return;
        }

        // Store tokens in database
        NewGoogleToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'company_id' => $company->id,
            ],
            [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'google_id' => $socialiteUser->id ?? null,
                'name' => $socialiteUser->name ?? null,
                'email' => $socialiteUser->email ?? $user->email,
                'verification_status' => 'verified',
            ]
        );

        Log::info('Google Calendar: Tokens stored for user ' . $user->id);
    }

}
