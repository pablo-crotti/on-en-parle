<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

use App\Http\Controllers\AdminMessagesController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\PhoneCallController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\IndexRoomController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactFormController;

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

// !*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!* USER APP *!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!

// *************** ======= PAGE GENERATING ROUTES 
Route::get('/', function () {
    return  Inertia::render('Index/container');
})->name('index');

Route::get('/contact', function () {
    return  Inertia::render('Contact/container');
})->name('contact');

Route::get('/questions/{id}', function ($id) {
    return  Inertia::render('Chat/container')->with('id', $id);
})->name('questions');

Route::get('/transmissions', function () {
    return  Inertia::render('Transmissions/container');
})->name('transmissions');

Route::get('/calendar', function () {
    return  Inertia::render('Calendar/container');
})->name('calendar');

// *************** ======= DATA GET 
Route::get('/index/room', [IndexRoomController::class, 'indexRoom']);

Route::get('/chat/rooms', [ChatController::class, 'rooms']);

Route::get('/chat/rooms-list', [ChatController::class, 'roomsList']);

Route::get('/chat/room/{roomId}', [ChatController::class, 'room']);

Route::get('/chat/room/{roomId}/messages', [ChatController::class, 'messages']);

Route::get('/chat/room/{chatId}/likes', [LikesController::class, 'sumChatLikes']);

Route::get('/transmissions/dates', [CalendarController::class, 'getBroadcasteDates']);

Route::get('/calendar/{year}/{month}/{day}', [CalendarController::class, 'hasTransmission']);

Route::get('/audio/{filename}', function ($filename) {
    $path = storage_path('app/public/rec/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'audio/webm',
    ]);
});

// *************** ======= DATA POST 
Route::post('/contact/send', [ContactFormController::class, 'sendMail'])->name('contact.send');

Route::post('/chat/room/{roomId}/message', [ChatController::class, 'newMessage']);

Route::post('/chat/like/{messageId}', [LikesController::class, 'like']);

Route::post('/chat/unlike/{messageId}', [LikesController::class, 'unlike']);

Route::post('/store-audio/{roomId}', [AudioController::class, 'store']);

// !*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!* ADMIN APP *!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});



// *********************************************** ======= PROGRAMS 
// *************** ======= PAGE GENERATING ROUTES
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/admin/programs/list', function () {
    return  Inertia::render('Admin/Programs/Programs/container');
})->name('listPrograms');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/admin/programs/live', function () {
    return  Inertia::render('Admin/Programs/Live/container');
})->name('live');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/admin/programs/new', function () {
    return  Inertia::render('Admin/Programs/NewProgram/container');
})->name('newProgramm');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/programs/program/{id}', function ($id) {
        return  Inertia::render('Admin/Programs/Programs/programDetail')
            ->with('id', $id);
    })->name('program-detail');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/programs/modify/{id}', function ($id) {
        return  Inertia::render('Admin/Programs/Programs/modifyProgram-container')->with('id', $id);
    })->name('modify');

// *************** ======= DATA GET
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/prochaine-emission', [LiveController::class, 'getNearestBroadcast']);

// *************** ======= DATA POST
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/chat/room/edit/{roomId}', [ProgramController::class, 'updateRoom']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/emission/{roomId}/live', [LiveController::class, 'setLive']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/chat/room/new', [ProgramController::class, 'newRoom']);

// ====================================================================================================---

// *********************************************** =======  INBOX
// *************** ======= PAGE GENERATING ROUTES
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/reception/inbox/{id}', [ChatAdminController::class, 'showChatRoom'])
    ->name('inbox');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/reception/archives/{id}', function ($id) {
        $userId = auth()->user()->id;
        return  Inertia::render(
            'Admin/Reception/Archives/container',
            ['userId' => $userId, 'id' => $id]
        );
})->name('archives');

// ====================================================================================================---

// *********************************************** =======  MANAGEMENT
// *************** ======= PAGE GENERATING ROUTES
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/administration/control/{id}', [ChatAdminController::class, 'showControlChatRoom'])
    ->name('control');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/administration/animator/{id}', [ChatAdminController::class, 'showAnimatorChatRoom'])
    ->name('animator');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/administration/management/{id}', [ChatAdminController::class, 'showManagementChatRoom'])->name('management');

// *************** ======= DATA POST
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/phone-calls', [PhoneCallController::class, 'store']);

// ====================================================================================================---

// *********************************************** =======  USERS
// *************** ======= PAGE GENERATING ROUTES
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/users', function () {
    return  Inertia::render('Users/container');
})->name('users');

// *************** ======= DATA GET
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/admin/users', [UserController::class, 'getUsers']);

// *************** ======= DATA POST
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/create-user', [UserController::class, 'create']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/admin/users/delete/{id}', [UserController::class, 'destroy']);

// ====================================================================================================---

// *********************************************** =======  ADMIN GENERAL DATA GET
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/prochaines-emissions', [ProgramController::class, 'getUpcomingBroadcasts']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->get('/chat/room/{roomId}/admin-messages', [AdminMessagesController::class, 'getAdminMessages']);

// *********************************************** =======  ADMIN GENERAL DATA POST
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/AdminInbox/message/{id}/update', [ChatAdminController::class, 'updateMessageStatus']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/AdminInbox/message/{id}/content', [ChatAdminController::class, 'updateMessageContent']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/AdminInbox/message/{id}/delete', [ChatAdminController::class, 'deleteMessage']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->post('/chat/room/{roomId}/admin-message', [AdminMessagesController::class, 'newMessage']);










Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('test/{id}', function ($id) {
    $userId = auth()->user()->id;

    return Inertia::render('Test/container', [
        'userId' => $userId,
    ]);
});
