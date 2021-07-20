<?php

use App\Models\User;

$namespaceAdmin = config('add.controllers') . '\\Admin';


// Роуты для админки
Route::namespace($namespaceAdmin)
    ->prefix(config('add.admin', 'dashboard'))
    ->name('admin.')
    ->middleware(['auth', 'role:' . User::getRoleAdmin()])
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
    Route::get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


    // Add routes get
    Route::get('delete-img', 'FileController@deleteImg')->name('delete-img');
    Route::get('delete-file', 'FileController@delete')->name('delete-file');
    Route::get('sidebar-mini', 'MainController@sidebarMini')->name('sidebar-mini');
    Route::get('get-cookie', 'MainController@getCookie')->name('get-cookie');
    Route::get('pagination', 'MainController@pagination')->name('pagination');
    Route::get('locale/{locale}', 'MainController@locale')->name('locale');
    Route::get('/', 'MainController@index')->name('main');

    // Add routes post
    Route::post('get-slug', 'MainController@getSlug')->name('get-slug');
});
