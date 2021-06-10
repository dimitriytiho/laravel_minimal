<?php


namespace App\Classes;

use App\Contracts\Registry as RegistryContract;


// Паттерн реестр позволяет добавить данные в контейнер и получить их в любом месте приложения
class Registry implements RegistryContract
{
    private $properties = [];


    /**
     * Set property value.
     *
     * @param string|int $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $this->properties[$key] = $value;
    }


    /**
     * Get property value.
     *
     * @param string|int $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->properties[$key] ?? null;
    }


    /**
     * @return array
     *
     * Получить все свойства
     */
    public function all()
    {
        return $this->properties;
    }
}
