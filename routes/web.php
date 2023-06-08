<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminMessagesController;
use App\Http\Controllers\AudioController;
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
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactFormController;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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

Route::get('/register', [RegisteredUserController::class, 'create'])
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

Route::get('/admin/programs/list', function () {
    return  Inertia::render('Admin/Programs/Programs/container');
})->name('listPrograms');

Route::get('/admin/programs/live', function () {
    return  Inertia::render('Admin/Programs/Live/container');
})->name('live');

Route::get('/admin/programs/new', function () {
    return  Inertia::render('Admin/Programs/NewProgram/container');
})->name('newProgramm');

Route::get('/admin/reception/inbox', function () {
    return  Inertia::render('Admin/Reception/Inbox/container');
})->name('inbox');

Route::get('/admin/reception/archives', function () {
    return  Inertia::render('Admin/Reception/Archives/container');
})->name('archives');

Route::get('/admin/administration/animator', function () {
    return  Inertia::render('Admin/Administration/Animator/container');
})->name('animator');

Route::get('/admin/administration/control/{id}', [ChatAdminController::class, 'showControlChatRoom'])->name('control');

Route::get('/admin/administration/animator/{id}', [ChatAdminController::class, 'showAnimatorChatRoom'])->name('animator');

Route::get('/admin/administration/management', function () {
    return  Inertia::render('Admin/Administration/Management/container');
})->name('manage');

Route::get('/admin/administration/management/{id}', [ChatAdminController::class, 'showManagementChatRoom'])->name('management');

Route::post('/chat/room/edit/{roomId}', [ProgramController::class, 'updateRoom']);

Route::post('/store-audio/{roomId}', [AudioController::class, 'store']);
//Emission détaillée
Route::get('/admin/programs/program/{id}', function ($id) {
    return  Inertia::render('Admin/Programs/Programs/programDetail')->with('id', $id);
})->name('program-detail');

//Page de modification d'une émission
Route::get('/admin/programs/modify/{id}', function ($id) {
    return  Inertia::render('Admin/Programs/Programs/modifyProgram-container')->with('id', $id);
})->name('modify');


Route::get('/index/room', [IndexRoomController::class, 'indexRoom']);
Route::get('/chat/rooms', [ChatController::class, 'rooms']);
Route::get('/chat/rooms-list', [ChatController::class, 'roomsList']);
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
Route::post('/chat/room/new', [ProgramController::class, 'newRoom']);
Route::get('/emission/{no}/status/{id}', [AdminChatController::class, 'getElementsByStatus']);
Route::get('/audio/{filename}', function ($filename) {
    $path = storage_path('app/public/rec/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'audio/webm',
    ]);
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('test/{id}', function ($id) {
    $userId = auth()->user()->id;

    return Inertia::render('Test/container', [
        'userId' => $userId,
    ]);
});

Route::get('/chat/room/{roomId}/admin-messages', [AdminMessagesController::class, 'getAdminMessages']);
Route::post('/chat/room/{roomId}/admin-message', [AdminMessagesController::class, 'newMessage']);


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/users', function () {
    return  Inertia::render('Users/container');
})->name('users');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/admin/users', [UserController::class, 'getUsers']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->post('/admin/users/delete/{id}', [UserController::class, 'destroy']);

Route::post('/create-user', [UserController::class, 'create']);


////////// CONTACT FORM

Route::get('/contact', function () {
    return  Inertia::render('Contact/container');
})->name('contact');

Route::post('/contact/send', [ContactFormController::class, 'sendMail'])->name('contact.send');
