<?php

namespace App\Http\Controllers\Admin;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;

class AttributeController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);


        // Связанная таблица
        $this->belongTable = 'properties';

        // Связанный маршрут
        $this->belongRoute = 'property';


        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.properties'), route("{$this->viewPath}.property.index"));
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['kebab']}.index"));
        });
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
            'id',
        ];

        // Параметры Get запроса
        $get = request()->query();
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;

        // Метод для поиска и сортировки запроса БД
        $values = $this->dbSort::getSearchSort($queryArr, $this->info['table'], $this->info['model'], $this->info['view'], $this->pagination);


        // Название вида
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->info['view']}";

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
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->template}";

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));


        // Получаем елементы таблицы родителя
        if (Schema::hasTable($this->belongTable)) {
            $all = DB::table($this->belongTable)->whereNull('deleted_at')->pluck('title', 'id');

            // Проверяем есть ли данные у родителя, если нет, то предлагаем создать их
            if (!$all->count()) {
                return redirect()
                    ->route("admin.{$this->belongRoute}.create")
                    ->with('info', __('a.create_parent_element'));
            }
        }

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'all'));
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
            'property_id' => "required|integer|exists:{$this->belongTable},id",
            //'title' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Checkbox сохраним 1 или 0
        $data['default'] = empty($data['default']) ? '0' : '1';

        // Создаём экземпляр модели
        $values = app()->make($this->info['model']);

        // Заполняем модель новыми данными
        $values->fill($data);

        // Сохраняем элемент
        $values->save();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['kebab']}.edit", $values->id)
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
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->template}";

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));

        // Получаем елементы таблицы родителя
        $all = null;
        if (Schema::hasTable($this->belongTable)) {
            $all = DB::table($this->belongTable)->whereNull('deleted_at')->pluck('title', 'id');
        }

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values', 'all'));
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
            'property_id' => "required|integer|exists:{$this->belongTable},id",
            //'title' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Checkbox сохраним 1 или 0
        $data['default'] = empty($data['default']) ? '0' : '1';

        // Заполняем модель новыми данными
        $values->fill($data);

        // Обновляем элемент
        $values->update();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['kebab']}.edit", $values->id)
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

        // Удаляем элемент
        $values->delete();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['kebab']}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
    }
}
