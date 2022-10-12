<?php

use App\Http\Controllers\RegisterController;
use App\Mail\Invitation;
use App\Models\Invite;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use League\Flysystem\RootViolationException;

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
    return view('welcome');
});

Route::post('/register', RegisterController::class)->name('register');

Route::post('invite', function(){
    Mail::to(request()->email)->send(new Invitation());
    Invite::create(['email' => request()->email]);
});

Route::get('todo', function(){
    return view('todo', ['todos' => Todo::all()]);
})->name('todo.index');

Route::post('todo', function(){
    Todo::query()
        ->create([
            'title' => request()->title,
            'description' => request()->description,
            'assigned_to_id' => request()->assignedTo
        ]);

    return redirect()->route('todo.index');
})->name('todo.store');
