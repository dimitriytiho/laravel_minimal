<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Route, File};
use App\Http\Controllers\{HomeController, PageController};
use App\Http\Controllers\Auth\LoginController;


//$namespace = config('add.controllers');


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


// Если выключен веб-сайт, то редирект на страницу /error.php, кроме админской части
if (env('OFF_WEBSITE') && !Str::contains(request()->path(), config('add.admin', 'dashboard'))) {
    Route::redirect(request()->path(), '/error.php');
}


// Если в запросе /public, то редирект на без /public
if (Str::contains(request()->url(), '/public')) {
    Route::redirect(request()->path(), str_replace('/public', '', request()->url()));
}


// Admin routes
if (File::isFile($routesAdmin = __DIR__ . '/admin.php')) {
    require_once $routesAdmin;
}


// Ex - маршруты чтобы отдать или получить данные из БД
/*Route::prefix('ex')->namespace($namespace)->name('ex.')->group(function () {
    Route::get('json/{code_enter}/{table}', 'ExController@json')->name('get');
    Route::get('get/{code_enter}/{domain}/{table}', 'ExController@get')->name('get');
});*/


// Routes
Auth::routes();
Route::get('auth/logout', [LoginController::class, 'logout'])->name('logout-get');
Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('/{slug}', [PageController::class, 'show'])->name('page');
Route::get('/', [PageController::class, 'index'])->name('index');
