<?php

namespace App\Http\Controllers\Admin;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;

class PageController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Указать методы из моделей, если есть связанные элементы не удалять (первый параметр: метод из модели, второй: название маршрута)
        $relatedManyToManyDelete = $this->relatedManyToManyDelete = [
            [$this->info['table'], $this->info['slug']],
        ];

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['slug']}.index"));
        });

        view()->share(compact('relatedManyToManyDelete'));
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
        $values = $this->dbSort::getSearchSort($queryArr, $get, $this->info['table'], $this->info['model'], $this->info['view'], $this->pagination);


        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->info['action']}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['table']);
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
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['action']);

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
            'slug' => "required|string|unique:{$this->info['table']}|max:255",
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
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info['model']::findOrFail($id);

        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['action']);

        // Дерево элементов
        $tree = $this->info['model']::get()->toTree();

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values', 'tree'));
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

        // Валидация
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => "required|string|unique:{$this->info['table']},slug,{$id}|max:255",
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


        // Если есть связанные элементы не удалять
        if ($this->relatedManyToManyDelete) {
            foreach ($this->relatedManyToManyDelete as $relatedTable) {
                if (!empty($relatedTable[0]) && $values->{$relatedTable[0]} && $values->{$relatedTable[0]}->count()) {
                    return redirect()
                        ->route("admin.{$this->info['slug']}.edit", $id)
                        ->withErrors(__('s.remove_not_possible') . ', ' . __('s.there_are_nested') . __('a.id'));
                }
            }
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
