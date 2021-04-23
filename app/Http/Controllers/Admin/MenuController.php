<?php

namespace App\Http\Controllers\Admin;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Связанная таблица
        $this->belongTable = 'menu_groups';

        // Связанный маршрут
        $this->belongRoute = 'menu-group';


        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.menu_groups'), route("{$this->viewPath}.menu-group.index"));
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['slug']}.index"));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Получаем элемент родителя из куки
        $currentParentId = request()->cookie("{$this->info['table']}_id");
        if ($currentParentId) {
            $currentParent = DB::table($this->belongTable)->find($currentParentId);
        }

        // Если не получили элемент родителя
        if (empty($currentParent)) {

            // Получаем первый элемент родителя
            $currentParent = DB::table($this->info['table'])->first();

            // Если нет родительских элементов, то предлагаем создать их
            if (!$currentParent) {
                return redirect()
                    ->route("admin.{$this->belongRoute}.create")
                    ->with('info', __('a.create_parent_element'));
            }

            // Записать куку навсегда (5 лет)
            return redirect()
                ->route("admin.{$this->info['slug']}.index")
                ->withCookie(cookie()->forever("{$this->info['table']}_id", $currentParent->id)
                );
        }

        // Из связанной таблицы получаем все элементы, где id ключи, title значения
        $parentValues = DB::table($this->belongTable)
            ->pluck('title', 'id');

        // Добавляем 0 ключ в объект - название связанной таблицы
        $parentValues->prepend($this->info['table'], 0);


        $values = null;

        // Поиск. Массив гет ключей для поиска
        $queryArr = [
            'title',
            'slug',
            'status',
            'sort',
            'parent_id',
            'id',
        ];

        // Параметры Get запроса
        $get = request()->query();
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;

        // Если в родительской таблице нет элементов, то ничего нельзя добавить
        if ($currentParent) {

            // Метод для поиска и сортировки запроса БД
            $values = $this->dbSort::getSearchSort($queryArr, $get, $this->info['table'], $this->info['model'], $this->info['view'], $this->pagination, 'belong_id', $currentParent->id);
        }

        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->info['action']}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['table']);
        return view($view, compact('title', 'values', 'queryArr', 'col', 'cell', 'currentParent', 'parentValues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Получаем элемент родителя из куки
        $currentParentId = request()->cookie("{$this->info['table']}_id");
        if ($currentParentId) {
            $currentParent = DB::table($this->belongTable)->find($currentParentId);
        }

        // Если не получили элемент родителя
        if (empty($currentParent)) {

            // Получаем первый элемент родителя
            $currentParent = DB::table($this->info['table'])->first();

            // Если нет родительских элементов, то предлагаем создать их
            if (!$currentParent) {
                return redirect()
                    ->route("admin.{$this->belongRoute}.create")
                    ->with('info', __('a.create_parent_element'));
            }

            // Записать куку навсегда (5 лет)
            return redirect()
                ->route("admin.{$this->info['view']}.index")
                ->withCookie(cookie()->forever("{$this->info['table']}_id", $currentParent->id)
                );
        }

        // Из связанной таблицы получаем все элементы, где id ключи, title значения
        $parentValues = DB::table($this->belongTable)
            ->pluck('title', 'id');

        // Добавляем 0 ключ в объект - название связанной таблицы
        $parentValues->prepend($this->info['table'], 0);


        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['action']);

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'currentParent', 'parentValues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'belong_id' => "required|integer|exists:{$this->belongTable},id",
            'title' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Создаём экземкляр модели
        $values = new $this->info['model']();

        // Заполняем модель новыми данными
        $values->fill($data);

        // Сохраняем элемент
        $values->save();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.edit", $values->id)
            ->with('success', __('s.created_successfully', ['id' => $values->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Получаем элемент родителя из куки
        $currentParentId = request()->cookie("{$this->info['table']}_id");
        if ($currentParentId) {
            $currentParent = DB::table($this->belongTable)->find($currentParentId);
        }

        // Если не получили элемент родителя
        if (empty($currentParent)) {

            // Получаем первый элемент родителя
            $currentParent = DB::table($this->info['table'])->first();

            // Если нет родительских элементов, то предлагаем создать их
            if (!$currentParent) {
                return redirect()
                    ->route("admin.{$this->belongRoute}.create")
                    ->with('info', __('a.create_parent_element'));
            }

            // Записать куку навсегда (5 лет)
            return redirect()
                ->route("admin.{$this->info['slug']}.index")
                ->withCookie(cookie()->forever("{$this->info['table']}_id", $currentParent->id)
                );
        }


        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info['model']::findOrFail($id);

        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['action']);

        // Дерево элементов
        $tree = $this->info['model']::where('belong_id', $currentParent->id)
            ->get()
            ->toTree();

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values', 'currentParent', 'tree'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info['model']::findOrFail($id);

        $rules = [
            'belong_id' => "required|integer|exists:{$this->belongTable},id",
            'title' => 'required|string|max:100',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Заполняем модель новыми данными
        $values->fill($data);

        // Фиксируем дерево
        $values::fixTree();

        // Обновляем элемент
        $values->update();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.edit", $values->id)
            ->with('success', __('s.saved_successfully', ['id' => $values->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info['model']::findOrFail($id);

        // Если есть связи, то вернём ошибку
        if ($values->{$this->info['table']} && $values->{$this->info['table']}->count()) {

            return redirect()
                ->route("admin.{$this->info['slug']}.edit", $id)
                ->with('error', __('s.remove_not_possible') . ', ' . __('s.there_are_nested') . __('a.id'));
        }

        // Удаляем элемент
        $values->delete();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
    }
}
