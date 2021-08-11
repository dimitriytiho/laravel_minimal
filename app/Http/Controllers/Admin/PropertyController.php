<?php

namespace App\Http\Controllers\Admin;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;

class PropertyController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['kebab']}.index"));
        });


        // Указать методы из моделей, если есть связанные элементы многие ко многим (первый параметр: метод из модели, второй: название маршрута, третий: название колонки (id), четвёртый: название колонки (title)), пятый: название метода сохранения (по-умолчанию sync)
        $relatedManyToManyEdit = $this->relatedManyToManyEdit = [
            ['attributes', $this->info['kebab'], 'id', 'title', 'saveMany'],
        ];


        // Указать методы из моделей, если есть связанные элементы не удалять (первый параметр: метод из модели, второй: название маршрута)
        $relatedManyToManyDelete = $this->relatedManyToManyDelete = [
            ['attributes', 'attribute'],
        ];


        view()->share(compact('relatedManyToManyEdit', 'relatedManyToManyDelete'));
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
            //'title' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Checkbox сохраним 1 или 0
        $data['default'] = empty($data['default']) ? '0' : '1';

        // Создаём экземкляр модели
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
                    if (Schema::hasColumns($related[0], [$related[2], $related[3]])) {
                        if (Schema::hasColumn($related[0], 'deleted_at')) {
                            $all[$related[0]] = DB::table($related[0])->whereNull('deleted_at');
                        } else {
                            $all[$related[0]] = DB::table($related[0]);
                        }
                        $all[$related[0]] = $all[$related[0]]->pluck($related[3], $related[2]);
                    }
                }
            }
        }

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
            //'title' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Checkbox сохраним 1 или 0
        $data['default'] = empty($data['default']) ? '0' : '1';


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


        // Транзакция на 2 попытки
        DB::transaction(function () use ($id, $values) {

            // Удаляем связи
            DB::table('propertable')
                ->where('property_id', $id)
                ->delete();

            // Удаляем элемент
            $values->delete();
        }, 2);

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['kebab']}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
    }
}
