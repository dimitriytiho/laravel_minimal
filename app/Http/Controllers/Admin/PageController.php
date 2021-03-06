<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Property, LastData};
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;

class PageController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);


        /*
         * Указать методы из моделей, если есть связанные элементы многие ко многим:
         *
         * 0 - метод из модели.
         * 1 - название маршрута.
         * 2 - название колонки (id).
         * 3 - название колонки (title).
         * 4 - название метода сохранения, по-умолчанию sync.
         * 5 - название таблицы, если не совпадает с метод из модели.
         */
        $this->relatedManyToManyEdit = [
            //['categories', 'category', 'id', 'title', 'sync'],
        ];


        // Указать методы из моделей, если есть связанные элементы не удалять (первый параметр: метод из модели, второй: название маршрута)
        $this->relatedManyToManyDelete = [
            [$this->info->table, $this->info->kebab],
        ];


        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info->table), route("{$this->viewPath}.{$this->info->kebab}.index"));
        });

        view()->share([
            'relatedManyToManyEdit' => $this->relatedManyToManyEdit,
            'relatedManyToManyDelete' => $this->relatedManyToManyDelete,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        // Метод для поиска и сортировки запроса БД
        $values = $this->dbSort::getSearchSort($queryArr, $this->info->table, $this->info->model, $this->info->view, $this->pagination);


        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->info->view}";

        $title = __('a.' . $this->info->table);
        return view($view, compact('title', 'values', 'queryArr', 'col', 'cell'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->template}";

        $title = __('a.' . $this->info->action) . ' ' . Str::lower(__('a.' . $this->info->table));

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title'));
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
            'title' => 'required|string|max:255',
            'slug' => "required|string|unique:{$this->info->table}|max:255",
            'parent_id' => 'nullable|integer|min:0',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Создаём экземпляр модели
        $values = app()->make($this->info->model);

        // Заполняем модель новыми данными
        $values->fill($data);

        // Сохраняем элемент
        $values->save();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info->kebab}.edit", $values->id)
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
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info->model::findOrFail($id);

        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->template}";

        $title = __('a.' . $this->info->action) . ' ' . Str::lower(__('a.' . $this->info->table));

        // Дерево элементов
        $tree = $this->info->model::get()->toTree();


        // Свойства (получаем только для этого класса)
        $properties = Property::whereType($this->info->model)->pluck('title', 'id');


        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });


        // Если есть связанные элементы, то получаем их
        $all = [];
        if ($this->relatedManyToManyEdit) {
            foreach ($this->relatedManyToManyEdit as $related) {
                if (!empty($related[0]) && !empty($related[2]) && !empty($related[3])) {
                    $relatedTable = $related[5] ?? $related[0];
                    if (Schema::hasColumns($relatedTable, [$related[2], $related[3]])) {
                        $all[$relatedTable] = DB::table($relatedTable);
                        if (Schema::hasColumn($relatedTable, 'deleted_at')) {
                            $all[$related[0]] = $all[$relatedTable]->whereNull('deleted_at');
                        }
                        if (Schema::hasColumn($relatedTable, 'status')) {
                            $all[$related[0]] = $all[$relatedTable]->whereStatus($this->active);
                        }
                        $all[$related[0]] = $all[$relatedTable]->pluck($related[3], $related[2]);
                    }
                }
            }
        }

        return view($view, compact('title', 'values', 'tree', 'properties', 'all'));
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
        $values = $this->info->model::findOrFail($id);

        // Валидация
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => "required|string|unique:{$this->info->table},slug,{$id}|max:255",
            'parent_id' => 'nullable|integer|min:0',
            'sort' => 'required|integer|min:1|max:65535',
        ];
        $request->validate($rules);
        $data = $request->all();


        // Сохраним прошлые данные
        LastData::saveData($id, $this->info->table);


        // Если есть связанные элементы, то синхронизируем их
        if ($this->relatedManyToManyEdit) {
            foreach ($this->relatedManyToManyEdit as $related) {
                if (!empty($related[0])) {

                    // Метод сохранения
                    $methodSave = $related[4] ?? 'sync';

                    // Удаляем связи многие ко многим
                    $values->{$related[0]}()->$methodSave($request->{$related[0]});
                }
            }
        }


        // Связь со свойствами
        $values->properties()->sync($request->properties);

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
            ->route("admin.{$this->info->kebab}.edit", $values->id)
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
        $values = $this->info->model::findOrFail($id);


        // Если есть связанные элементы не удалять
        if ($this->relatedManyToManyDelete) {
            foreach ($this->relatedManyToManyDelete as $related) {
                if (!empty($related[0]) && $values->{$related[0]} && $values->{$related[0]}->count()) {
                    return redirect()
                        ->route("admin.{$this->info->kebab}.edit", $id)
                        ->withErrors(__('s.remove_not_possible') . ', ' . __('s.there_are_nested') . __('a.id'));
                }
            }
        }


        // Если есть связанные элементы, то удаляем их
        if ($this->relatedManyToManyEdit) {
            foreach ($this->relatedManyToManyEdit as $related) {
                if (!empty($related[0]) && $values->{$related[0]} && $values->{$related[0]}->count()) {

                    if (!isset($related[4]) || isset($related[4]) && $related[4] === 'sync') {

                        // Удаляем связи многие ко многим
                        $values->{$related[0]}()->sync([]);

                    } else {

                        // Удаляем связь многие к одному
                        $values->{$related[0]}()->delete();
                    }
                }
            }
        }

        // Связь со свойствами
        $values->properties()->sync([]);

        // Удаляем элемент
        $values->delete();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info->kebab}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
    }
}
