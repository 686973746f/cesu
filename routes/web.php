<?php

/*

Include mga bagong controller dito

*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\RecordsController;

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

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    // your routes
    //Route::get('/addrecord', [AddRecordsController::class, 'index'])->name('addrecord');
    //Route::post('/addrecord', [AddRecordsController::class, 'store']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('records', RecordsController::class);
    Route::resource('/forms', FormsController::class);

    Route::post('/export', [FormsController::class, 'export'])->name('forms.export');
});

Route::get('/ajaxGetUserRecord/{id}', [FormsController::class, 'ajaxGetUserRecord']);

//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return view('home');
    }
    else {
        return view('auth.login');
    }    
});