<?php

use App\Ad;
use Faker\Generator as Faker;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 * */
$factory->define(Ad::class, function (Faker $faker) {
    $photos = [];

    foreach (range(0, mt_rand(0, 4)) as $n) {
        $photos[] = 'https://api.lorem.space/image?w=300&h=300&n=' . $n . $faker->randomLetter();
    }

    return [
        'name' => $faker->sentence,
        'description' => $faker->realText,
        'price' => $faker->numberBetween(200, 3000),
        'photos' => $photos,
        'created_at' => $faker->dateTime,
    ];
});
