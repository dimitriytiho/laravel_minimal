<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
//use ReCaptcha\ReCaptcha;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Добавляем Google ReCaptcha в валидатор
        /*Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $recaptcha = new ReCaptcha(config('add.recaptcha_secret_key'));
            $resp = $recaptcha->verify($value, request()->ip());

            return $resp->isSuccess();
        });*/

        // Валидатор номера телефона (допускаются +()- и цифры)
        Validator::extend('tel', function($attribute, $value, $parameters) {
            return preg_match('#^[\+\(\)\- 0-9]+$#', $value) && strlen($value) > 10;
        });

        // Если индексирование сайта выключено
        /*if (config('add.not_index_website')) {
            header('X-Robots-Tag: noindex,nofollow'); // Заголовок запрещающий индексацию сайта
        }*/

        $file = config('add.file');
        $img = config('add.img');

        // Передаём в виды
        view()->share(compact('file', 'img'));
    }
}
