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
        if (Lang::has("{$fileLang}.{$str}")) {
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
        // Получаем все настройки и кэшируем запрос
        $settings = cache()->rememberForever('users', function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$settingName] ?? null;
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


    /**
     *
     * @return string
     *
     * Использовать скрипты в контенте, они будут перенесены вниз страницы.
     * $content - контент, в котором удалиться скрипты и перенести их вниз страницы.
     * В виде получить скрипты из переменной $scriptsFromContent.
     */
    public static function downScripts($content)
    {
        if ($content) {
            $scripts = [];
            $pattern = '#<script.*?>.*?</script>#si';
            preg_match_all($pattern, $content, $scripts);

            if (!empty($scripts[0])) {
                $scripts = implode("\n", $scripts[0]);
                view()->share(['scriptsFromContent' => $scripts]);
                $content = preg_replace($pattern, '', $content);
            }
        }
        return $content;
    }


    /**
     *
     * @return string
     *
     * Подключает файл из resources/views/replace с название написаном в контенте ##!!!file_name (название файла file_name.blade.php).
     *
     * $content - если передаётся контент, то в нём будет искаться ##!!!file_name и заменяется на файл из папки resources/views/replace.
     * $values - Можно передать данные в подключаемый файл.
     */
    public static function inc($content, $values = null)
    {
        if ($content) {

            $path = 'replace';
            $search = '##!!!';
            $pattern = '/(?<=' . $search . ')\w+/';
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            if ($matches) {
                foreach ($matches as $v) {
                    if (!empty($v[0])) {
                        $view = $path . '.' . $v[0];
                        $patternInner = '/' . $search . $v[0] . '/';

                        if (view()->exists($view)) {
                            $output = view($view, compact('values'))->render();
                            $content = preg_replace($patternInner, $output, $content, 1);
                        } else {
                            $content = preg_replace($patternInner, '', $content);
                        }
                    }
                }
            }
        }
        return $content;
    }


    /**
     *
     * @return string
     *
     * Возвращает строку: Url, Email, IP пользователя.
     * $referer - передать true, если нужно вывести страницу, с которой перешёл пользователь, необязательный параметр.
     */
    public static function dataUser()
    {
        $email = auth()->check() && auth()->user()->email ? '. Email: ' . auth()->user()->email . '.' : null;
        return "Url: " . request()->url() . "{$email} IP: " . request()->ip();
    }
}
