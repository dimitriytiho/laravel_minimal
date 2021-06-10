<?php


namespace App\Contracts;


interface Registry
{
    /**
     * Set property value.
     *
     * @param string|int $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null);


    /**
     * Get property value.
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);


    /**
     * Get all properties.
     *
     * @return array
     */
    public function all();
}
