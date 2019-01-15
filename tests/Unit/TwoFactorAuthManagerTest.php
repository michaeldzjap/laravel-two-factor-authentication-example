<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MichaelDzjap\TwoFactorAuth\TwoFactorAuthManager;

class TwoFactorAuthManagerTest extends TestCase
{
    /**
     * Verify that the correct default provider is returned.
     *
     * @return void
     */
    public function testDefaultProvider() : void
    {
        $manager = resolve(TwoFactorAuthManager::class);

        $this->assertEquals('null', $manager->getDefaultDriver());

        $this->assertInstanceOf(
            \MichaelDzjap\TwoFactorAuth\Providers\NullProvider::class,
            $manager->provider()
        );
    }
}
