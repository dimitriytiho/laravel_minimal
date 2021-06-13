<?php

namespace App\Traits;


/**
 * Паттерн Singleton позволяет создать один экземпляр класса.
 *
 * Наследовать через use App\Traits\SingletonTrait;
 * В наследуемом классе создать объект через $ob = НаследуемыйКласс::instance();
 */
trait SingletonTrait
{
    private static $instance;
    //private static $instances = [];


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
    public function __wakeup() {}


    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
        /*$className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];*/
    }
}
