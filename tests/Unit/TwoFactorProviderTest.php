<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider;
use Tests\TestCase;

class TwoFactorProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The two-factor authentication provider implementation.
     *
     * @var \MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider
     */
    private $provider;

    /**
     * Set up for all tests.
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->provider = resolve(TwoFactorProvider::class);
    }

    /**
     * Verify if two-factor authentication is enabled for a given user.
     *
     * @return void
     */
    public function testTwoFactorAuthEnabled() : void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->twoFactor()->create();

        $this->assertFalse($this->provider->enabled($user1));
        $this->assertTrue($this->provider->enabled($user2));
    }
}
