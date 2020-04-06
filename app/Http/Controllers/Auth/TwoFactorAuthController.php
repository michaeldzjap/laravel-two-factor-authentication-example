<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use MichaelDzjap\TwoFactorAuth\Http\Controllers\TwoFactorAuthenticatesUsers;

class TwoFactorAuthController extends Controller
{
    use TwoFactorAuthenticatesUsers;

    /**
     * The maximum number of attempts to allow.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * The number of minutes to throttle for.
     *
     * @var int
     */
    protected $decayMinutes = 1;

    /**
     * Where to redirect users after two-factor authentication passes.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
}
