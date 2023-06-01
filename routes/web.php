<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\PhoneCallController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::get('/questions/{id}', function ($id) {
    return  Inertia::render('Chat/container')->with('id', $id);
});

Route::get('/transmissions', function () {
    return  Inertia::render('Transmissions/container');
})->name('transmissions');

Route::get('/chat/rooms', [ChatController::class, 'rooms']);
Route::get('/chat/room/{roomId}', [ChatController::class, 'room']);
Route::get('/chat/room/{roomId}/messages', [ChatController::class, 'messages']);
Route::post('/chat/room/{roomId}/message', [ChatController::class, 'newMessage']);
Route::post('/chat/like/{messageId}', [LikesController::class, 'like']);
Route::post('/chat/unlike/{messageId}', [LikesController::class, 'unlike']);
Route::get('/chat/room/{chatId}/likes', [LikesController::class, 'sumChatLikes']);




Route::get('/AdminInbox/{id}', [ChatAdminController::class, 'showChatRoom'])->name('chatroom.show');
Route::post('/AdminInbox/message/{id}/update', [ChatAdminController::class, 'updateMessageStatus']);
Route::post('/AdminInbox/message/{id}/content', [ChatAdminController::class, 'updateMessageContent']);
Route::post('/AdminInbox/message/{id}/delete', [ChatAdminController::class, 'deleteMessage']);

Route::post('/phone-calls', [PhoneCallController::class, 'store']);






