<?php

namespace App\Http\Controllers\Admin;

use App\Services\Info\InfoController;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuGroupController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);


        // Связанная таблица
        $this->belongTable = 'menus';

        // Связанный маршрут
        $this->belongRoute = 'menu';


        // Получаем данные о текущем классе
        $this->info = app()->make(InfoController::class);


        // Указать методы из моделей, если есть связанные элементы не удалять (первый параметр: метод из модели, второй: название маршрута)
        $this->relatedManyToManyDelete = [
            [$this->belongTable, $this->belongRoute],
        ];

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info->table), route("{$this->viewPath}.{$this->info->kebab}.index"));
        });

        view()->share([
            'info' => $this->info,
            'belongTable' => $this->belongTable,
            'belongRoute' => $this->belongRoute,
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
            'sort',
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

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values'));
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
        ];
        $request->validate($rules);
        $data = $request->all();

        // Заполняем модель новыми данными
        $values->fill($data);

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

        // Удаляем элемент
        $values->delete();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        session()->flash('success', __('s.removed_successfully', ['id' => $values->id]));

        // Если удаляется id, который записан в куку, то удалим куку
        $cookie = request()->cookie("{$this->belongTable}_id");
        if ($cookie == $id) {
            return redirect()->route("admin.{$this->info->kebab}.index")
                ->withCookie(cookie()->forget("{$this->belongTable}_id"));
        }

        return redirect()->route("admin.{$this->info->kebab}.index");
    }
}
