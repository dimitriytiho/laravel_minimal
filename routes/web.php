<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, PageController};
use App\Http\Controllers\Auth\LoginController;

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


// Если выключен веб-сайт, то редирект на страницу /error.php
if (env('OFF_WEBSITE')) {
    Route::domain(env('APP_URL'))->group(function () {
        header('Location: ' . env('APP_URL') . '/error.php');
        die;
    });
}

// Если в запросе /public, то сделается редирект на без /public
$url = request()->url();
$public = '/public';
if (stripos($url, $public) !== false) {
    $url = str_replace($public, '', $url);
    header("Location: {$url}");
    die;
}

// Admin routes
if (is_file($fileAdmin = __DIR__ . '/admin.php')) {
    require_once $fileAdmin;
}

// Namespace Controllers
//$namespace = config('add.controllers') . '\\';


// Routes
Auth::routes();
Route::get('auth/logout', [LoginController::class, 'logout'])->name('logout_get');
Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('/{slug}', [PageController::class, 'show'])->name('page');
Route::get('/', [PageController::class, 'index'])->name('index');
