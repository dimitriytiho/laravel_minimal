<?php


namespace App\Support;

use App\Contracts\UserLog as UserLogInterface;

class UserLog implements UserLogInterface
{
    /**
     *
     * @return array
     * Возвращает массив тэгов.
     */
    public static function tags()
    {
        return config('add.user_log_tags');
    }


    /**
     *
     * @return void
     * Записать поведение пользователя в БД.
     *
     * @param string $tag - тэг (раздел, класс...) поведения. Теги должных быть из массива конфига add.user_log_tags, если другой, то сохраним тэг undefined.
     * @param string $text - текс для сохранения (лучше всего использовать __METHOD__).
     * @param object $user - объект пользователя, необязательный параметр.
     */
    public static function save($tag, $text, $user = null)
    {
        $tags = self::tags();
        $tag = in_array($tag, $tags) ? $tag : 'undefined';

        activity()
            ->causedBy($user)
            ->useLog($tag)
            ->log($text);
    }
}
