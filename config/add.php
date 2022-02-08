<?php

use Illuminate\Support\Str;


return [

    // Кол-во элементов на странице для пагинации
    'pagination' => 24,


    // Основной цвет сайта
    'primary' => '#ff5e5e',


    // Публичные папки
    'file' => '/file', // Для файлов
    'img' => '/img', // Для картинок
    'imgDefault' => '/img/default/no_image.jpg',
    'imgPath' => public_path('img'),
    'imgExt' => ['png', 'jpg', 'jpeg', 'gif', 'svg'],


    /*
     * Название шаблона для видов, js, sass
     * в resources/js/index.js и resources/sass/index.scss (в двух местах) написать название шаблона
     * Создать файл params_название_шаблона.scss, например: resources/sass/config/params_new.scss
     * В resources/sass/config/mixins.scss изменить @import 'params_new';
     */
    'template' => 'default',


    // Протокол и домен
    'protocol' => Str::before(env('APP_URL'), '://'),
    'domain' => Str::after(env('APP_URL'), '://'),


    // Статусы страниц
    'statuses' => [
        'inactive', // Неактивная должна стоять первой
        'active', // Активная должна стоять второй
        'removed', // Удалённая должно стоять третьим
    ],


    // Теги для логирования пользователей
    'user_log_tags' => [
        'important',
        'auth',
        'admin',
    ],


    // SEO Настройки
    'not_index_website' => env('NOT_INDEX_WEBSITE'), // Если не нужно индексировать сайт, то true, если нужно, то false

    // Перечислить те страницы, которые не нужно индексировать
    'disallow' => [
        'search',
        'search?*',
        //'success-page',
    ],

    // Список таблиц информационных блоков (для обновления веб-сайта и пр.), у таблиц в структуре БД должны быть статусы как в массиве statuses, этого же файла.
    'list_of_information_block' => [

        // Имена таблиц в БД
        'tables' => [
            //'pages',
        ],

        // Имена маршрутов из /routes/web.php, маршруты должны быть именованные
        'routes' => [ // Очерёдность должна быть как в массиве tables
            //'page',
        ],
    ],

    // Список страниц, которые нужно добавить в sitemap, которых нет в БД
    'list_pages_for_sitemap_no_db' => [
        'items' => [
            //'contact-us',
            //'order',
        ],

        // Имена маршрутов из /routes/web.php, маршруты должны быть именованные
        'routes' => [
            //'contact_us',
            //'order',
        ],
    ],


    'models' => 'App\\Models',
    'models_path' => app_path('Models'),
    'controllers' => 'App\\Http\\Controllers',
    'support' => 'App\\Support',
    'services' => 'App\\Services',

    // Настройки из файла /.env, т.к. после кэширования они будут возвращать null
    'dev' => env('APP_DEV', 'OmegaKontur'),
    'enter' => env('APP_ENTER', 'login'),
    'admin' => env('APP_ADMIN', 'dashboard'),
    'app_email' => env('APP_EMAIL'),
    'recaptcha_public_key' => env('RECAPTCHA_PUBLIC_KEY'),
    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),


    'development' => [
        'Developer' => 'Dmitriy Konovalov',
        'Email' => 'dimitriytiho@gmail.com',
        'Facebook' => 'https://www.facebook.com/dimitriytiho',
        'From' => 'Moscow, Russia',
        'Language' => 'Russian',
        'Doctype' => 'HTML5',
        'Framework' => 'Laravel, Bootstrap',
        'IDE' => 'PHPStorm, Visual Studio, Sublime Text',
        'Brand' => 'OmegaKontur',
    ],
];
