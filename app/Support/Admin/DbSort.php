<?php


namespace App\Support\Admin;

use App\Support\Func;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DbSort
{
    /**
     *
     * @return object
     *
     * Возвращает результат запроса к БД, с учётом поиска и сортировки, с пагинацией.
     *
     * @param string $queryArr - колонки для поиска.
     * @param array $get - Get параметры из запроса.
     * @param string $table - название таблицы.
     * @param string|object $model - название модели или часть запроса (объектом).
     * @param string $view - название вида.
     * @param string $view - название вида.
     * @param int|string $perPage - кол-во для пагинации, если в сессии есть кол-во (session('pagination')), то в первую очередь возьмётся оттуда.
     * @param string $whereColumn - дополнительное условие выборки, название колонки, необязательный параметр.
     * @param string $whereValue - дополнительное условие выборки, значение колонки, необязательный параметр.
     * @param string|array $withModelMethod - передать связанный метод из модели, необязательный параметр, возможно несколько массивом.
     */
    public static function getSearchSort(array $queryArr, $table, $model, $view, $perPage, $withModelMethod = null)
    {
        $get = request()->query();
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;
        $perPage = session('pagination') ?: $perPage;

        // Если в запросе есть обратная косая черта, то добавляем ещё одну для корректного поиска
        if (Str::contains($cell, '\\')) {
            $cell = addCslashes('App\Models\User', '\\');
        }

        // Значения по-умолчанию для сортировки
        $columnSort = 'id';
        $order = 'desc';

        // Если сессия сортировки не существует, то сохраним значения по-умолчанию
        if (!session()->exists("admin_sort.{$view}")) {
            session()->put("admin_sort.{$view}.{$columnSort}", $order);
        }


        // Если передаётся через Get сортировка, то проверим есть ли такая колонка в таблице
        if ($get) {
            $columnSortGet = key($get);
            if (Schema::hasColumn($table, $columnSortGet)) {

                // Если есть такая колонка, то сохраним её
                $columnSort = $columnSortGet;
                $order = $get[$columnSort];
                if ($order === 'asc' || $order === 'desc') {

                    // Удалим прошлое значение
                    session()->forget("admin_sort.{$view}");

                    // Сохраним новое
                    session()->put("admin_sort.{$view}.{$columnSort}", $order);
                }
            }
        }



        // ФОРМИРУЕМ ЗАПРОС
        // Если название модели строкой, то сформируем запрос
        if (is_string($model)) {
            $values = $model::query();
        } else {
            $values = $model;
        }


        // Если есть строка поиска
        if ($col && in_array($col, $queryArr) && $cell) {

            // Если есть связанная таблица
            if ($withModelMethod) {
                $values = $values->with($withModelMethod);
            }
            $values = $values->where($col, 'LIKE', "%{$cell}%");


        // Иначе выборка всех элементов из БД
        } else {

            // Если есть связанная таблица
            if ($withModelMethod) {
                $values = $values->with($withModelMethod);
            }
        }


        // Если есть колонка status в таблице, то можем показать только со статусом removed.
        $values = self::remoteMode($table, $values);

        // Возвращаем объект с сортировкой и пагинацией
        return $values->orderBy($columnSort, $order)->paginate($perPage);
    }


    /**
     *
     * @return object
     * Показать элементы со статусом removed, возвращает объект.
     *
     * @param string $table - название таблицы.
     * @param object $values - сырой объект.
     */
    private static function remoteMode($table, $values)
    {
        if (Schema::hasColumn($table, 'status')) {

            // Показывать удалённые элементы
            $remoteMode = Func::site('remote_mode');
            $statusRemoved = config('add.statuses')[2] ?? 'removed';

            // Показывать удалённые элементы, если выбрано в настройках remote_mode
            if ($remoteMode) {
                $values = $values->whereStatus($statusRemoved);
            } else {
                $values = $values->where('status', '!=', $statusRemoved);
            }
        }
        return $values;
    }


    /**
     *
     * @return string
     *
     * Возвращает вид иконок сортировки.
     *
     * @param string $columnSort - название колонки сортировки.
     * @param string $view - название вида.
     * @param string $route - маршрут вида.
     */
    public static function viewIcons($columnSort, $view, $route)
    {
        $langAsc = __('a.asc');
        $langDesc = __('a.desc');
        $routeAsc = route("admin.{$route}.index", "{$columnSort}=asc");
        $routeDesc = route("admin.{$route}.index", "{$columnSort}=desc");
        $activeAsc = session()->get("admin_sort.{$view}.{$columnSort}") === 'asc' ? 'active' : null;
        $activeDesc = session()->get("admin_sort.{$view}.{$columnSort}") === 'desc' ? 'active' : null;

        return <<<S
<span class="filter-icons">
    <a href="{$routeAsc}" class="{$activeAsc}" title="{$langAsc}">
        <i class="fas fa-arrow-up"></i>
    </a>
    <a href="{$routeDesc}" class="{$activeDesc}" title="{$langDesc}">
        <i class="fas fa-arrow-down"></i>
    </a>
</span>
S;
    }
}
