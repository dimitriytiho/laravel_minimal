<?php

namespace App\Http\Controllers\Admin;

use App\Models\LastData;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends AppController
{
    private $keyNoEdit;


    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Массив названий настроек, название которые нельзя изменять
        $this->keyNoEdit = $this->info['model']::keyNoEdit() ?? [];

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['slug']}.index"));
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Поиск. Массив гет ключей для поиска
        $queryArr = [
            'key',
            'value',
            'type',
            'section',
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
            'key' => 'required|string|max:255',
        ];
        $request->validate($rules);
        $data = $request->all();


        // Если тип checkbox, то сохраним 1 или 0
        if (isset($data['type']) && $data['type'] === (config('admin.setting_type')[1] ?? 'checkbox')) {
            $data['value'] = empty($data['value']) ? '0' : '1';
        }

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
            ->route("admin.{$this->info['slug']}.edit", $values->id)
            ->with('success', __('s.created_successfully', ['id' => $values->id]));
    }


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

        // Если title запрещён к редактированию
        $disabledDelete = in_array($values->key, $this->keyNoEdit) ? 'readonly' : null;

        // Название вида
        $view = "{$this->viewPath}.{$this->info['slug']}.{$this->template}";

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values', 'disabledDelete'));
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
            'key' => "required|string|unique:{$this->info['table']},key,{$id}|max:255",
        ];
        $request->validate($rules);
        $data = $request->all();


        // Сохраним прошлые данные
        LastData::saveData($id, $this->info['table']);

        // Если тип checkbox, то сохраним 1 или 0
        if (isset($data['type']) && $data['type'] === (config('admin.setting_type')[1] ?? 'checkbox')) {
            $data['value'] = empty($data['value']) ? '0' : '1';
        }

        // Заполняем модель новыми данными
        $values->fill($data);

        // Если title запрещён к редактированию
        if (in_array($values->key, $this->keyNoEdit) && $values->title != $request->title) {

            // Сообщение об ошибке
            return redirect()
                ->route("admin.{$this->info['slug']}.edit", $values->id)
                ->withErrors(__('s.something_went_wrong'));
        }

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

        // Если key запрещён к редактированию
        if (in_array($values->key, $this->keyNoEdit)) {

            // Сообщение об ошибке
            return redirect()
                ->route("admin.{$this->info['slug']}.edit", $values->id)
                ->withErrors(__('s.something_went_wrong'));
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
