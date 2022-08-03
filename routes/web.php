<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

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

Route::get('/game', [GameController::class, 'index'])->name('game');
Route::get('/game-result', [GameController::class, 'result'])->name('game-result');
Route::post('/game-start', [GameController::class, 'start'])->name('game-start');

Route::get('/', function () {
    return redirect()->route('game');
});
