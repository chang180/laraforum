<?php

use App\Support\PostFixtures;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('post-content', function () {
        return PostFixtures::getFixtures()->random();
    })->name('api.autofill-post-content');
});

Route::middleware('web')->group(function () {});
