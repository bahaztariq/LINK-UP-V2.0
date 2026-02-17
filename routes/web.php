<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\Auth\SocialiteController;

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');


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



    Route::get('/reels', function () {
        return view('reels');
    })->name('reels');
    
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    
    Route::get('/user/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::get('/messages', [\App\Http\Controllers\ConversationController::class, 'index'])->name('messages');
    Route::get('/conversations/{id}', [\App\Http\Controllers\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/create/{user}', [\App\Http\Controllers\ConversationController::class, 'store'])->name('conversation.create');
    Route::post('/messages/send', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::put('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'update'])->name('messages.update');

    // Invitation Routes
    Route::post('/invitations/generate', [\App\Http\Controllers\InvitationController::class, 'generate'])->name('invitations.generate');
    Route::get('/invitations/qr/{token}', [\App\Http\Controllers\InvitationController::class, 'showQR'])->name('invitations.qr');
    Route::get('/invitations/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
});
