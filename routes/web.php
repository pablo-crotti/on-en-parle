<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikesController;
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


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/questions/{id}', function ($id) {
    return  Inertia::render('Chat/container')->with('id', $id);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/transmissions', function () {
    return  Inertia::render('Transmissions/container');
})->name('transmissions');

Route::middleware('auth:sanctum')->get('/chat/rooms', [ChatController::class, 'rooms']);
Route::middleware('auth:sanctum')->get('/chat/room/{roomId}', [ChatController::class, 'room']);
Route::middleware('auth:sanctum')->get('/chat/room/{roomId}/messages', [ChatController::class, 'messages']);
Route::middleware('auth:sanctum')->post('/chat/room/{roomId}/message', [ChatController::class, 'newMessage']);
Route::middleware('auth:sanctum')->post('/chat/like/{messageId}', [LikesController::class, 'like']);
Route::middleware('auth:sanctum')->post('/chat/unlike/{messageId}', [LikesController::class, 'unlike']);
Route::middleware('auth:sanctum')->get('/chat/room/{chatId}/likes', [LikesController::class, 'sumChatLikes']);