<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MakeUserTwoFactorAuthenticableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The user instance.
     *
     * @var User
     */
    private $user;

    /**
     * Set up for all tests.
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();

        // $this->user = factory(\App\User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
