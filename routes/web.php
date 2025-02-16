<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', [ChatController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/chats/store/{user}', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::delete('/chats/{chat}', [ChatController::class, 'destroy'])->name('chats.destroy');
    
    Route::post('/chats/{chat}/messages', [MessageController::class, 'chatMessageStore'])->name('chats.messages.store');
    Route::post('/groups/{group}/messages', [MessageController::class, 'groupMessageStore'])->name('groups.messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::post('/groups/{group}/messages/typing', [MessageController::class, 'groupMessageTyping'])->name('groups.messages.typing');
    Route::post('/chats/{chat}/messages/typing', [MessageController::class, 'chatMessageTyping'])->name('chats.messages.typing');

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts/store/{user?}', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::put('/groups/update/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::post('/groups/store', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/edit/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::get('/groups/{group}/members', [GroupController::class, 'showMembers'])->name('groups.show.members');
    Route::delete('/groups/{group}/remove/{user}', [GroupController::class, 'removeMember'])->name('groups.remove.member');
    Route::delete('/groups/leave/{group}', [GroupController::class, 'leave'])->name('groups.leave');
    Route::delete('/groups/delete/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('/profiles/{user}', [ProfileController::class, 'show'])->name('profiles.show');
    Route::put('/profiles/{user}/update', [ProfileController::class, 'update'])->name('profiles.update');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/users', [SearchController::class, 'users'])->name('search.users');
    Route::get('/search/members/{group}', [SearchController::class, 'members'])->name('search.members');
    Route::get('/search/chats', [SearchController::class, 'chats'])->name('search.chats');
    Route::get('/search/contacts', [SearchController::class, 'contacts'])->name('search.contacts');
    Route::get('/search/groups', [SearchController::class, 'groups'])->name('search.groups');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

