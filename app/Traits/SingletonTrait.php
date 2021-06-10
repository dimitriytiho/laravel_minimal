<?php

namespace App\Traits;

use App\Support\Func;

/**
 * Паттерн Singleton позволяет создать один экземпляр класса.
 */
trait SingletonTrait
{
    private static $instances = [];


    /**
     * Конструктор Одиночки всегда должен быть скрытым, чтобы предотвратить
     * создание объекта через оператор new.
     */
    protected function __construct() {}


    /**
     * Одиночки не должны быть клонируемыми.
     */
    protected function __clone() {}


    /**
     * Одиночки не должны быть восстанавливаемыми из строк.
     */
    public function __wakeup()
    {
        Func::getError('Cannot unserialize a singleton', __METHOD__);
    }


    public static function instance()
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }
}
