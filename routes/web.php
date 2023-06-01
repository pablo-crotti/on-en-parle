<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikesController;
<<<<<<< HEAD
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\PhoneCallController;

=======
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\IndexRoomController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
>>>>>>> 7c26c1ac94b5d86bbc9a302251b26211cd10874a

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

Route::get('/index/room', [IndexRoomController::class, 'indexRoom']);
Route::get('/chat/rooms', [ChatController::class, 'rooms']);
Route::get('/chat/room/{roomId}', [ChatController::class, 'room']);
Route::get('/chat/room/{roomId}/messages', [ChatController::class, 'messages']);
Route::post('/chat/room/{roomId}/message', [ChatController::class, 'newMessage']);
Route::post('/chat/like/{messageId}', [LikesController::class, 'like']);
Route::post('/chat/unlike/{messageId}', [LikesController::class, 'unlike']);
Route::get('/chat/room/{chatId}/likes', [LikesController::class, 'sumChatLikes']);
<<<<<<< HEAD











=======
Route::get('/transmissions/dates', [CalendarController::class, 'getBroadcasteDates']);
Route::get('/calendar/{year}/{month}/{day}', [CalendarController::class, 'hasTransmission']);
>>>>>>> 7c26c1ac94b5d86bbc9a302251b26211cd10874a
