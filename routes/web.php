<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\PhoneCallController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\IndexRoomController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\LiveController;

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

// Route::get('/', [IndexController::class, 'redirectToQuestion'])->name('index');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::get('/questions/{id}', function ($id) {
    return  Inertia::render('Chat/container')->with('id', $id);
})->name('questions');

Route::get('/', function () {
    return  Inertia::render('Index/container');
})->name('index');

Route::get('/transmissions', function () {
    return  Inertia::render('Transmissions/container');
})->name('transmissions');

Route::get('/calendar', function () {
    return  Inertia::render('Calendar/container');
})->name('calendar');

Route::get('/Admin/Programs', function () {
    return  Inertia::render('Admin/Programs/container');
})->name('calendar');

Route::get('/admin/programs/live', function () {
    return  Inertia::render('Admin/Programs/Live/container');
})->name('live');

Route::get('/admin/programs/new', function () {
    return  Inertia::render('Admin/Programs/NewProgram/container');
})->name('newProgramm');

Route::get('/index/room', [IndexRoomController::class, 'indexRoom']);
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
Route::get('/transmissions/dates', [CalendarController::class, 'getBroadcasteDates']);
Route::get('/calendar/{year}/{month}/{day}', [CalendarController::class, 'hasTransmission']);
Route::get('/prochaine-emission', [LiveController::class, 'getNearestBroadcast']);
Route::post('/emission/{roomId}/live', [LiveController::class, 'setLive']);
Route::post('/chat/room/new', [ChatController::class, 'newRoom']);