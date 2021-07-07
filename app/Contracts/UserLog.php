<?php


namespace App\Contracts;


interface UserLog
{
    /**
     *
     * @return array
     * Возвращает массив тэгов.
     */
    public static function tags();


    /**
     *
     * @return void
     * Записать поведение пользователя в БД.
     *
     * @param string $tag - тэг (раздел, класс..) поведения.
     * @param string $text - текс для сохранения (лучше всего использовать __METHOD__).
     * @param object $user - объект пользователя, необязательный параметр.
     */
    public static function save($tag, $text, $user = null);
}
