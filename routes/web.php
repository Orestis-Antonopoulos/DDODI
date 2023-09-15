<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GameController;
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


Auth::routes();
Route::post('/', [LoginController::class, 'loginOrRegister'])->name('loginOrRegister');
Route::get('/', function () {return view('auth/login');});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/game/new', 'GameController@newGame')->name('game.new');
Route::get('/game/continue', 'GameController@continueGame')->name('game.continue');
// Route::get('/game', 'GameController@index')->name('game.index');
Route::match(['get', 'post'], '/game', [GameController::class, 'index'])->middleware('auth')->name('game.index');
Route::post('/game/randomStats', 'GameController@randomStats')->name('game.randomStats');
Route::post('/game/randomXP', 'GameController@randomXP')->name('game.randomXP');

//New Game Randomize stats:
Route::post('/game/randomStats', [GameController::class, 'randomStats'])->name('game.randomStats');
Route::post('/game/easy', [GameController::class, 'easy'])->name('game.easy');
Route::post('/game/medium', [GameController::class, 'medium'])->name('game.medium');
Route::post('/game/hard', [GameController::class, 'hard'])->name('game.hard');

//in-Game
// Route::post('/game/play', [GameController::class, 'play'])->name('game.play');
Route::match(['get', 'post'], '/game/play', [GameController::class, 'play'])->name('game.play');
Route::post('/game/attack', [GameController::class, 'attack'])->name('game.attack');
Route::match(['get', 'post'], '/game/loot', [GameController::class, 'loot'])->name('game.loot');
