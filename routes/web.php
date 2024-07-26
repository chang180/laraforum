<?php

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Resources\CommentResource;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class)->only(['create', 'store']);

    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'destroy', 'update']);

    Route::post('/likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
});

Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');
