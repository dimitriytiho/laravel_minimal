<?php


namespace App\Support\Admin;

use Illuminate\Support\Str;

class App
{
    /**
     *
     * @return string
     *
     * Возвращает строку в латинице из кириллицы для URL.
     *
     * @param string $str - строка.
     * @param int|null $length - возвращаемая длина, по-умолчанию 82 символов, необязательный параметр.
     */
    public static function cyrillicToLatin($str, $length = 82)
    {
        return Str::limit(Str::slug($str), $length, '');
    }
}
