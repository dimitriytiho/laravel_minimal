<?php


namespace App\Traits;


/*
 * Здесь дополнительные методы, чтобы добавить в Eloquent ORM новые команды.
 * Для использования наследовать этот trait в модели.
 */
trait ModelScopesTrait
{
    /**
     * Scope для элементов со статусом active.
     *
     * Использование ->active()
     */
    public function scopeActive($query)
    {
        return $query->where('status', config('add.statuses')[1] ?? 'active');
    }


    /**
     * Scope сортировки по сортировке (меньшие кверху) и по id (большие кверху).
     *
     * Использование ->order()
     *
     * @param string $column - колонка, по которой сортировать.
     * @param string $direction - направление сортировки, по-умолчанию desc, может быть asc.
     */
    public function scopeOrder($query, $column = 'sort', $direction = 'asc')
    {
        return $query->orderBy($column, $direction)->orderBy('id', 'desc');
    }


    /**
     * Проверить в scope: сейчас попадает ли в промежуток времени.
     *
     * Использование ->betweenTime()
     */
    public function scopeBetweenTime($query)
    {
        return $query
            ->where('start', '<', now())
            ->where('end', '>', now());
    }



    /**
     *
     * @return array
     * Возвращает колонки данной модели.
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
