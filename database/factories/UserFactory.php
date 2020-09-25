<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MichaelDzjap\TwoFactorAuth\Models\TwoFactorAuth;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function twoFactor(): Factory
    {
        return $this
            ->state([
                'mobile' => $this->faker->phoneNumber,
            ])
            ->afterCreating(function (User $user): void {
                TwoFactorAuth::factory()->create(['user_id' => $user->id]);
            });
    }
}
