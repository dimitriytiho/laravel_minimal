<?php


namespace App\Support\Admin;

use App\Support\Func;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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


    /**
     *
     * @return array
     * Возвращает namespace всех моделей в массиве.
     *
     * @param bool $firstEmpty - передать true, чтобы первый элемент массив был пустой.
     * @param bool $namespace - по-умолчанию namespace дополняем, если нужны только названия моделей, то передать false.
     */
    public static function getModels($firstEmpty = false, $namespace = true)
    {
        $all = File::allFiles(config('add.models_path'));
        $namespace = $namespace ? config('add.models') . '\\' : null;
        if ($all) {
            if ($firstEmpty) {
                $models[] = ' ';
            }
            foreach ($all as $file) {
                $models[] = $namespace . pathinfo($file)['filename'];
            }
        }
        return $models ?? null;
    }


    /**
     *
     * @return bool
     * Разрешения ролей пользователей.
     *
     * @param string|null $permission - разрешение будет приведено к формату foo_str.
     */
    public static function canUser($permission)
    {
        return auth()->check() && $permission && auth()->user()->can(Str::snake($permission));
    }
}
