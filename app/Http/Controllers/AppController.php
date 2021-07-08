<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->pagination = config('add.pagination');

        // Только внутри этой конструкции работают некоторые методы
        $this->middleware(function ($request, $next) {

            // Сохраним Url, с которого перешёл пользователь из админки
            $this->saveAdminPreviousUrl();

            // Вручную аутентифицировать пользователя
            /*if (!auth()->check()) {
                $user = $this->userModel::find(1);
                auth()->login($user);
            }*/

            return $next($request);
        });
    }


    /**
     *
     * @return void
     *
     * Сохраняем в сессию страницу с которой пользователь перешёл из админки.
     */
    private function saveAdminPreviousUrl()
    {
        // Если пользователь авторизирован и url содержит админский префикс
        if (auth()->check() && Str::is('*' . config('add.admin') . '*', url()->previous())) {
            session()->put('back_link_admin', url()->previous());
        }
    }
}
