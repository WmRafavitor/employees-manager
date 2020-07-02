<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use Faker\Generator as Faker;

require_once __DIR__ . '/../faker_data/document.php';

$factory->define(Employee::class, function (Faker $faker) {
    $cpfs = cpfs();

    return [
        'name' => $faker->name,
        'date_birth' => $faker->date(),
        'hired_at' => $faker->date(),
        'sex' => rand(1, 10) % 2 == 0 ? 'm' : 'f',
        'role' => $faker->word,
        'document' => $cpfs[array_rand($cpfs, 1)],
        'email' => $faker->email,
        'phone' => $faker->tollFreePhoneNumber,
    ];
});
