<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Support\UserLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class EnterController extends AppController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function index(Request $request)
    {
        // Сообщение об открытой странице входа
        UserLog::save('admin', 'Open Admin Login Page');

        $title = __('s.login');
        return view('admin.enter.index', compact('title'));
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        // Если есть ключ Recaptcha и не локально запущен сайт
        if (config('add.env') !== 'local' && config('add.recaptcha_public_key')) {
            $rules += [
                'g-recaptcha-response' => 'required|recaptcha',
            ];
        }

        $request->validate($rules);
    }


    // Действия после успешной авторизации
    protected function authenticated(Request $request, $user)
    {
        // Записать ip пользователя в БД
        $user->saveIp();

        // Логируем авторизацию
        UserLog::save('auth', 'Authorization Admin Panel');
    }


    // Редирект поле авторизации
    protected function redirectPath()
    {
        return route('admin.main');
    }
}
