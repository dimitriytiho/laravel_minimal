<?php

namespace App\Traits;

// Паттерн Singleton позволяет создать один экземпляр класса
trait SingletonTrait
{
    private static $instance;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
