<?php

use App\Support\PostFixtures;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;




    Route::get('post-content', function(){
        return PostFixtures::getFixtures()->random();
    });

Route::middleware('api')->group(function(){

});
