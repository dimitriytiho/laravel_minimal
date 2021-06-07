<?php


namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static set($name, $value)
 * @method static get($name)
 * @method static getAll()
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
