<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about',['name' => 'tarik']);
});

Route::get('/contact', function () {
    return view('contact');
});





Route::get('/user/profile' , function(){
    return view('user');
})->name('profil');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    Route::resource('posts', \App\Http\Controllers\PostController::class);
    Route::resource('comments', \App\Http\Controllers\CommentController::class);
    Route::post('/reactions', [\App\Http\Controllers\ReactionController::class, 'toggle'])->name('reactions.toggle');
    Route::get('/friends', [\App\Http\Controllers\FriendsController::class, 'index'])->name('friendships.index');
    Route::resource('friendships', \App\Http\Controllers\FriendshipController::class);

    Route::get('/explore', function () {
        return view('explore');
    })->name('explore');

    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');

    Route::get('/messages', function () {
        return view('messages');
    })->name('messages');

    Route::get('/reels', function () {
        return view('reels');
    })->name('reels');
    
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    
    Route::get('/user/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});
