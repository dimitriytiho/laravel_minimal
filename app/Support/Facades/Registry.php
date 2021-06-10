<?php


namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static set($key, $value)
 * @method static get($key, $default)
 * @method static all()
 *
 * Паттерн реестр позволяет добавить данные в контейнер и получить их в любом месте приложения.
 */
class Registry extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'registry';
    }
}
