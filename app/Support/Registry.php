<?php


namespace App\Support;

// Паттерн реестр позволяет добавить данные в контейнер и получить их в любом месте приложения
class Registry
{
    private $properties = [];


    /**
     *
     * @param $name имя
     * @param $value значение
     *
     * Добавить свойство.
     */
    public function set($name, $value)
    {
        $this->properties[$name] = $value;
    }


    /**
     *
     * @param $name имя
     * Получить существующие свойство по имеми.
     */
    public function get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return null;
    }


    /**
     * @return array
     *
     * Получить все свойства
     */
    public function getAll()
    {
        return $this->properties;
    }
}
