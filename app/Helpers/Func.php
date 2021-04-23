<?php


namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\{Lang, Route};

class Func
{
    /**
     *
     * @return string
     *
     * Возвращается переводную фразу, если её нет, то входную строку.
     * $str - строка для перевода.
     * $fileLang - имя файла с переводом (без .php), необязательный параметр (по-умолчанию ищет в s.php).
     */
    public static function __($str, $fileLang = 's')
    {
        if ($fileLang && Lang::has("{$fileLang}.{$str}")) {
            return __("{$fileLang}.{$str}");
        }
        return $str;
    }


    /**
     *
     * @return string
     *
     * Возвращается маршрут, если он есть, иначе ссылка на главную.
     * $routeName - название маршрута.
     * $parameter - параметр в маршруте, необязательный параметр (если передаваемый параметр не существует, то маршрут всё равно будет возвращён).
     */
    public static function route($routeName, $parameter = null)
    {
        if (Route::has($routeName)) {
            return $parameter ? route($routeName, $parameter) : route($routeName);
        }
        return null;
    }


    /**
     *
     * @return string
     *
     * Возвращает настройку сайта из таблицы settings.
     * Func::site('name') - достать настройку.
     * $settingName - название настройки.
     */
    public static function site($settingName)
    {
        if (cache()->has('settings_for_site')) {
            $settings = cache()->get('settings_for_site');
        } else {
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            // Кэшируется запрос
            cache()->forever('settings_for_site', $settings);
        }

        if (!empty($settings[$settingName])) {
            return $settings[$settingName];
        }
        return null;
    }


    /*
     * Записывает в логи ошибку и выбрасывает исключение (если выбрано).
     *
     * $message - текст сообщения.
     * $method - передать __METHOD__.
     * $abort - выбросывать исключение, по-умолчанию true, необязательный параметр.
     * $error - в каком виде записать ошибку, может быть: emergency, alert, critical, error, warning, notice, info, debug. По-умолчанию error, необязательный параметр.
     */
    public static function getError($message, $method, $abort = true, $error = 'error')
    {
        $message = "{$message}. In {$method}";
        if (method_exists(logger(), $error)) {
            logger()->$error($message);
        }
        if ($abort) {
            abort('404', $message);
        }
    }
}
