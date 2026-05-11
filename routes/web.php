<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/boodschappen', function () {
  auth()->user()->unreadNotifications()
    ->where('data->type', 'boodschappen')
    ->get()->markAsRead();
  return view('shopping.index');
})->middleware(['auth'])->name('shopping');

Route::get('/notities', function () {
  auth()->user()->unreadNotifications()
    ->where('data->type', 'notities')
    ->get()->markAsRead();
  return view('notes.index');
})->middleware(['auth'])->name('notes');

Route::get('/agenda', function () {
  auth()->user()->unreadNotifications()
    ->where('data->type', 'agenda')
    ->get()->markAsRead();
  return view('agenda.index');
})->middleware(['auth'])->name('agenda');

Route::get('/fotos', function () {
  auth()->user()->unreadNotifications()
    ->where('data->type', 'fotos')
    ->get()->markAsRead();
  return view('photos.index');
})->middleware(['auth'])->name('photos');

Route::get('/chat', function () {
  auth()->user()->unreadNotifications()
    ->where('data->type', 'chat')
    ->get()->markAsRead();
  return view('chat.index');
})->middleware(['auth'])->name('chat');

Route::get('/instellingen', function () {
  return view('settings.index');
})->middleware(['auth'])->name('settings');

Route::get('/admin/gebruikers', function () {
  if (auth()->user()->role !== 'admin') {
    abort(403);
  }
  return view('admin.users');
})->middleware(['auth'])->name('admin.users');
