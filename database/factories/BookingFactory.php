<?php

use Faker\Generator as Faker;

$factory->define(App\Booking::class, function (Faker $faker) {
    $allDay = $faker->boolean(20);
    $time = rand(7, 17) . ':' . (rand(0, 1) > 0 ? '00' : '30');
    return [
        'title' => $faker->sentence(4),
        'description' => $faker->text(30),
        'job_type_id' => $faker->numberBetween(1, 7),
        'date' => $faker->dateTimeBetween('-3 weeks', '+3 weeks')->format('Y-m-d'),
        'time' => $allDay ? null : $time, // 07:00 - 16:00
        'all_day' => $allDay,
    ];
});
