<?php

namespace Tests\Browser;

use App\Jobs\SendSMSToken;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TwoFactorAuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Necessary otherwise user will stay logged in between tests...
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
    }

    /**
     * Verify that a user for which two-factor authentication is not enabled
     * will be redirected to the home page after login.
     *
     * @return void
     */
    public function testRedirectToHome(): void
    {
        $user = User::factory()->create([
            'email' => 'riley.martin@space.com',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }

    /**
     * Verify that a user for which two-factor authentication is enabled will be
     * redirected to the two-factor authentication page after login and will be
     * redirected to the home page after submitting a valid token.
     *
     * @return void
     */
    public function testRedirectToHomeAfterTwoFactorAuth(): void
    {
        $user = User::factory()->twoFactor()->create([
            'email' => 'riley.martin@space.com',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/auth/token')
                    ->type('token', 333)
                    ->press('Send Token')
                    ->assertPathIs('/home');
        });

        $this->assertQueued(SendSMSToken::class);
    }

    /**
     * Verify that a user for which two-factor authentication is enabled will be
     * redirected to the two-factor authentication page after login and will
     * receive a validation error after submitting an invalid token.
     *
     * @return void
     */
    public function testFailTwoFactorAuth(): void
    {
        $user = User::factory()->twoFactor()->create([
            'email' => 'riley.martin@space.com',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/auth/token')
                    ->type('token', 'abc')
                    ->press('Send Token')
                    ->assertPathIs('/auth/token')
                    ->assertSee('The token must be a number.');
        });

        $this->assertQueued(SendSMSToken::class);
    }
}
