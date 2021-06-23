<?php


namespace App\Traits;


/*
 * Здесь дополнительные методы, что добавить в Eloquent ORM новый команды.
 * Для использования наследовать этот trait в моделе.
 */
trait ModelScopesTrait
{
    /*
     * Scope для элементов с статусом active.
     *
     * Использование ->active()
     */
    public function scopeActive($query)
    {
        return $query->where('status', config('add.statuses')[1] ?: 'active');
    }


    /*
     * Scope сортировке по сортировке или любой другой.
     *
     * Использование ->order(), можно например ->order('title', 'asc')
     * $sort - название сортировки, по-умолчанию по сортировке, необязательный параметр.
     * $direction - напровление сортировки, по-умолчанию по desc, необязательный параметр.
     */
    public function scopeOrder($query, $sort = 'sort', $direction = 'desc')
    {
        return $query->orderBy($sort, $direction)->orderBy('id', $direction);
    }


    /*
     * Добавляет в запрос связь из привязанной моделе.
     *
     * Использование ->withActiveSort('pages') - параметром передать название связи.
     *
     * Scope для привязанной таблицы, с условиями:
     * статус active,
     * сортировка по-сортировке,
     */
    public function scopeWithActiveSort($query, $type)
    {
        return $query->with([$type => function ($query) {
            $query
                ->where('status', config('add.statuses')[1] ?: 'active')
                ->orderBy('sort')
                ->orderBy('id');
        }]);
    }


    /*
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
}
