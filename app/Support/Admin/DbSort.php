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
     * @param string $model - название модели.
     * @param string $view - название вида.
     * @param int|string $perPage - кол-во для пагинации, если в сессии есть кол-во (session('pagination')), то в первую очередь возьмётся оттуда.
     * @param string $whereColumn - дополнительное условие выборки, название колонки, необязательный параметр.
     * @param string $whereValue - дополнительное условие выборки, значение колонки, необязательный параметр.
     * @param string $withModelMethod - передать название связанного метода из модели, необязательный параметр.
     */
    public static function getSearchSort(array $queryArr, $get, $table, $model, $view, $perPage, $whereColumn = null, $whereValue = null, $withModelMethod = null)
    {
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;
        $perPage = session()->has('pagination') ? session('pagination') : $perPage;

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
        $get = request()->query();
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


        // Если нужно дополнительное условие выборки
        if ($whereColumn && $whereValue) {

            // Если есть строка поиска
            if ($col && in_array($col, $queryArr)) {
                $values = $model::where($whereColumn, $whereValue)
                    ->where($col, 'LIKE', "%{$cell}%")
                    ->orderBy($columnSort, $order);

            // Иначе выборка всех элементов из БД
            } else {

                // Если есть связанная таблица
                if ($withModelMethod) {
                    $values = $model::with($withModelMethod)
                        ->where($whereColumn, $whereValue)
                        ->orderBy($columnSort, $order);

                } else {

                    $values = $model::where($whereColumn, $whereValue)
                        ->orderBy($columnSort, $order);
                }
            }

        } else {

            // Если есть строка поиска
            if ($col && in_array($col, $queryArr) && $cell) {

                // Если есть связанная таблица
                if ($withModelMethod) {
                    $values = $model::with($withModelMethod)
                        ->where($col, 'LIKE', "%{$cell}%")
                        ->orderBy($columnSort, $order);

                } else {
                    $values = $model::where($col, 'LIKE', "%{$cell}%")
                        ->orderBy($columnSort, $order);
                }


            // Иначе выборка всех элементов из БД
            } else {

                // Если есть связанная таблица
                if ($withModelMethod) {
                    $values = $model::with($withModelMethod)
                        ->orderBy($columnSort, $order);

                } else {

                    $values = $model::orderBy($columnSort, $order);
                }
            }
        }

        // Если есть колонка status
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

        return $values->paginate($perPage);
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
