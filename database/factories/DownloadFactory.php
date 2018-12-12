<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Downloads::class, function (Faker $faker) {
    $url = $faker->imageUrl();
    $filepath = App\Downloads::makeRandFilepath();
    Storage::makeDirectory($filepath);
    $folder = Storage::disk('public')->path($filepath);
    Storage::disk('public')->makeDirectory($filepath);
    $filename = $faker->image(Storage::disk('local')->path('temp'), 500, 500, 'cats', false);
    Storage::move('temp/'. $filename, 'public/'. $filepath. '/downloadfile');
    $filesize = filesize($folder.'/downloadfile');
    return [
        'filename' => $filename,
        'filesize' => $filesize,
        'status' => App\Downloads::STATUS_WAIT,
        'filepath' => $filepath,
        'error_msg' =>  '',
        'url' => $url,
    ];
});

$factory->state(App\Downloads::class, 'without_file', function ($faker) {
    $filepath = App\Downloads::makeRandFilepath();
    Storage::makeDirectory($filepath);
    return [
        'status' => App\Downloads::STATUS_WAIT,
        'filepath' => $filepath,
        'error_msg' =>  '',
        'url' => $faker->imageUrl(),
    ];
});


$factory->state(App\Downloads::class, 'success', function ($faker) {
    return [
        'status' => App\Downloads::STATUS_SUCCESS,
    ];
});

$factory->state(App\Downloads::class, 'error', function ($faker) {
    return [
        'error_msg' =>  $faker->sentence,
        'status' => App\Downloads::STATUS_ERROR,
    ];
});