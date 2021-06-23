<?php


namespace App\Support;

use Illuminate\Support\Facades\File;

class Locale
{
    public $locales;
    private $currentLocale;
    private $langPath;


    private function __construct()
    {
        $this->currentLocale = app()->getLocale();
        $this->locales = config('add.locales');
        $this->langPath = resource_path('lang');
    }


    /**
     *
     * @return string
     *
     * Возвращает строку, в формате json c переводами из переводного файла.
     *
     * @param string $varName - название переменной для JS, по-умолчанию translations, необязательный параметр.
     * @param string $fileName - имя файла из lang папки (например resources/lang/en/js.php), по-умолчанию js, необязательный параметр.
     */
    public static function translationsJson($varName = 'translations', $fileName = 'js')
    {
        $self = new self();
        $locale = $self->currentLocale;
        $langPath = $self->langPath;

        if (!empty($locale) && $langPath) {
            $file = "{$langPath}/{$locale}/{$fileName}.php";
            if (File::exists($file)) {
                $part = "var $varName = ";
                $translations = require($file);
                $part .= json_encode($translations, JSON_UNESCAPED_UNICODE);
            }
            return $part . PHP_EOL;
        }
        return false;
    }
}
