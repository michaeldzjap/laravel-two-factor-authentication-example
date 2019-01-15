<?php

use Faker\Generator as Faker;

$factory->define(MichaelDzjap\TwoFactorAuth\TwoFactorAuth::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
