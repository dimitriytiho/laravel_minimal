<?php

use App\Services\Auth\Role;

$namespace = config('add.controllers');
$namespaceAdmin = $namespace . '\\Admin';


// Роуты для страницы входа в админ панель
/*Route::namespace($namespace)->name(env('APP_ENTER'))->group(function () {
    $keyRoute = env('APP_ENTER') . '/' . Func::site('key_admin');

    Route::get($keyRoute, 'Admin\EnterController@index');
    Route::post($keyRoute, 'Admin\EnterController@login')->name('_post');

});*/


// Роуты для админки
Route::namespace($namespaceAdmin)
    ->prefix(config('add.admin', 'dashboard'))
    ->name('admin.')

    // Проверяем: 1. Пользователь авторизирован. 2. У пользователя роль с доступом к админ панели. 3. У пользователя есть разрешение к текущему классу.
    ->middleware(['auth', 'role:' . implode('|', Role::ADMIN_PANEL_NAMES), 'can:' . request()->segment(2)])
    ->group(function () {


    // Add routes resource
    Route::resource('attribute', AttributeController::class)->except(['show']);
    Route::resource('file', FileController::class)->except(['show']);
    Route::resource('menu', MenuController::class)->except(['show']);
    Route::resource('menu-group', MenuGroupController::class)->except(['show']);
    Route::resource('page', PageController::class)->except(['show']);
    Route::resource('property', PropertyController::class)->except(['show']);
    Route::resource('setting', SettingController::class)->except(['show']);
    Route::resource('role', RoleController::class)->except(['show']);
    Route::resource('permission', PermissionController::class)->except(['show']);
    Route::resource('user', UserController::class)->except(['show']);

    // Website add controllers
    Route::match(['get'],'additionally', 'AdditionallyController@index')->name('additionally');
    Route::get('activity-log', 'ActivityLogController@index')->name('activity-log');
    Route::get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('log');


    // Add routes get
    Route::get('file/delete-img', 'FileController@deleteImg')->name('delete-img');
    Route::get('file/delete-file', 'FileController@delete')->name('delete-file');
    Route::get('main/sidebar-mini', 'MainController@sidebarMini')->name('sidebar-mini');
    Route::get('main/get-cookie', 'MainController@getCookie')->name('get-cookie');
    Route::get('main/get-session', 'MainController@getSession')->name('get-session');
    Route::get('main/locale/{locale}', 'MainController@locale')->name('locale');
    Route::get('main', 'MainController@index')->name('main');

    // Add routes post
    Route::post('main/get-slug', 'MainController@getSlug')->name('get-slug');
});
