<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSMSToken;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The two-factor authentication provider implementation.
     *
     * @var TwoFactorProvider
     */
    private $provider;

    /**
     * Create a new controller instance.
     *
     * @param  \MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider  $provider
     * @return void
     */
    public function __construct(TwoFactorProvider $provider)
    {
        $this->middleware('guest')->except('logout');

        $this->provider = $provider;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($this->provider->enabled($user)) {
            return $this->startTwoFactorAuthProcess($request, $user);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log out the user and start the two factor authentication state.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    private function startTwoFactorAuthProcess(Request $request, $user)
    {
        // Logout user, but remember user id
        auth()->logout();
        $request->session()->put(
            'two-factor:auth', array_merge(['id' => $user->id], $request->only('email', 'remember'))
        );

        $this->registerUserAndSendToken($user);

        return redirect()->route('auth.token');
    }

    /**
     * Provider specific two-factor authentication logic. In the case of
     * MessageBird we just want to send an authentication token via SMS.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    private function registerUserAndSendToken(User $user)
    {
        // Custom, provider dependend logic for sending an authentication token
        // to the user. In the case of MessageBird Verify this could simply be
        // resolve(TwoFactorProvider::class)->sendSMSToken($this->user)
        // Here we assume this function is called from a queue'd job
        dispatch(new SendSMSToken($user));
    }
}
