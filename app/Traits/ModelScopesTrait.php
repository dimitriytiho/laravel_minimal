<?php


namespace App\Traits;


/*
 * Здесь дополнительные методы, что добавить в Eloquent ORM новый команды.
 * Для использования наследовать этот trait в моделе.
 */
trait ModelScopesTrait
{
    /**
     * Scope для элементов с статусом active.
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
     */
    public function scopeOrder($query)
    {
        return $query->orderBy('sort')->orderBy('id', 'desc');
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
     * Возращает колонки данной модели.
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
