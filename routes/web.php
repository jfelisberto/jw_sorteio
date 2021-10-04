<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Matches;
use App\Http\Controllers\Players;
use App\Http\Controllers\Presences;
use App\Http\Controllers\Teams;
use App\Http\Controllers\Automations;

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

Route::get('/home', function () {
    $galery = [
        [
            'url' => asset('galery/campo_society.jpg'),
            'alt' => 'Campo societe',
            'active' => 'active'
        ],
        [
            'url' => asset('galery/campo_societey_arena_gavea.jpg'),
            'alt' => 'Arena da Gavea',
            'active' => false
        ],
        [
            'url' => asset('galery/campo_societey_sao_goncalo_do_rio_abaixo.jpg'),
            'alt' => 'Arena São Goncalo do Rio Abaixo',
            'active' => false
        ],
        [
            'url' => asset('galery/campo_societey_seitoco_moromizato.jpg'),
            'alt' => 'Arena Seitoco Moromizato',
            'active' => false
        ],
        [
            'url' => asset('galery/campo_society_arena_do_pai.jpeg'),
            'alt' => 'Arena do Pai',
            'active' => false
        ],
        [
            'url' => asset('galery/campo_society_montanha_club.jpeg'),
            'alt' => 'Arena Montanha Club',
            'active' => false
        ]
    ];
    return view('home', compact(['galery']));
})->name('home');


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::get('/matches', [Matches::class, 'index'])->name('matches');
Route::get('/match/create', [Matches::class, 'create'])->name('matchCreate');
Route::post('/match', [Matches::class, 'store'])->name('matchStore');
Route::get('/match/{id}', [Matches::class, 'show'])->name('matchShow');
Route::get('/match/{id}/edit', [Matches::class, 'edit'])->name('matchEdit');
Route::put('/match/{id}', [Matches::class, 'update'])->name('matchUpdate');
Route::put('/match/closed/{id}', [Matches::class, 'updateClosed'])->name('matchUpdateClosed');
Route::delete('/match/{id}', [Matches::class, 'destroy'])->name('matchDestroy');

Route::get('/players', [Players::class, 'index'])->name('players');
Route::get('/player/create', [Players::class, 'create'])->name('playerCreate');
Route::post('/player', [Players::class, 'store'])->name('playerStore');
Route::get('/player/{id}', [Players::class, 'show'])->name('playerShow');
Route::get('/player/{id}/edit', [Players::class, 'edit'])->name('playerEdit');
Route::put('/player/{id}', [Players::class, 'update'])->name('playerUpdate');
Route::delete('/player/{id}', [Players::class, 'destroy'])->name('playerDestroy');

Route::get('/presences', [Presences::class, 'index'])->name('presences');
Route::get('/presence/create', [Presences::class, 'create'])->name('presenceCreate');
Route::post('/presence', [Presences::class, 'store'])->name('presenceStore');
Route::get('/presence/{id}', [Presences::class, 'show'])->name('presenceShow');
Route::get('/presence/{id}/edit', [Presences::class, 'edit'])->name('presenceEdit');
Route::put('/presence/{id}', [Presences::class, 'update'])->name('presenceUpdate');
Route::put('/presence/confirm/{id}', [Presences::class, 'updateConfirm'])->name('presenceUpdateConfirm');
Route::delete('/presence/{id}', [Presences::class, 'destroy'])->name('presenceDestroy');

Route::get('/teams', [Teams::class, 'index'])->name('teams');
Route::get('/team/create', [Teams::class, 'create'])->name('teamCreate');
Route::post('/team', [Teams::class, 'store'])->name('teamStore');
Route::get('/team/{id}', [Teams::class, 'show'])->name('teamShow');
Route::get('/team/{id}/edit', [Teams::class, 'edit'])->name('teamEdit');
Route::put('/team/{id}', [Teams::class, 'update'])->name('teamUpdate');
Route::delete('/team/{id}', [Teams::class, 'destroy'])->name('teamDestroy');

Route::get('/automations', [Automations::class, 'index'])->name('automations');
Route::get('automation/presences/create', [Automations::class, 'createPresence'])->name('autoPresenceCreate');
Route::post('/automation/presences', [Automations::class, 'automationPresence'])->name('autoPresenceStore');
Route::get('automation/teams/create', [Automations::class, 'createTeam'])->name('autoTeamCreate');
Route::post('/automation/teams', [Automations::class, 'automationTeam'])->name('autoTeamStore');
