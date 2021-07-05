<?php

namespace App\Http\Controllers\Admin;

use App\Services\LastData\LastData;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\{DB, Hash, Schema};
use Illuminate\Support\Str;

class UserController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['slug']}.index"));
        });


        // Указать методы из моделей, если есть связанные элементы многие ко многим (первый параметр: метод из модели, второй: название маршрута, третий: название колонки (id), четвёртый: название колонки (title)), пятый: название метода сохранения (по-умолчанию sync)
        /*$relatedManyToManyEdit = $this->relatedManyToManyEdit = [
            ['roles', null, 'id', 'name'],
        ];*/

        //view()->share(compact('relatedManyToManyEdit'));
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
            'name',
            'email',
            'ip',
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

        // Роли пользователей
        $roles = DB::table('roles')->pluck('name', 'id');

        // Разрешения
        $permissions = DB::table('permissions')->pluck('name', 'id');

        // Картинка (получаем только картинки и для этого класса)
        $images = File::onlyImg()->whereType($this->info['model'])->pluck('path', 'id');

        // Добавить в начало коллекции
        $images->prepend(ltrim(config('add.imgDefault'), '/'), 0);


        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'roles', 'permissions', 'images'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Роль Admin может создавать Admin
        if (Str::contains($this->adminRoleName, $request->roles) && !auth()->user()->hasRole($this->adminRoleName)) {
            return redirect()->back()->withErrors(__('s.admin_choose_admin'));
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|unique:{$this->info['table']},email|max:255",
            'tel' => 'nullable|tel|max:255',
            'password' => 'required|string|min:6|same:password_confirmation',
            'accept' => 'accepted',
        ];
        $request->validate($rules);

        $request->merge([
            'password' => Hash::make($request->password), // Пароль хэшируется
            'accept' => $request->accept ? '1' : '0', // Сохранить чекбокс как 1
            'ip' => $request->ip(), // Добавить поле IP
        ]);
        $data = $request->all();

        // Создаём экземкляр модели
        $values = app()->make($this->info['model']);

        // Заполняем модель новыми данными
        $values->fill($data);

        // Сохраняем элемент
        $values->save();

        // Роли пользователя
        if ($request->roles) {
            $values->syncRoles($request->roles);
        } else {

            // Назначим роль User по-умолчанию
            $values->assignRole($this->info['model']::getRoleUser() ?? 'user');
        }

        // Разрешения
        $values->syncPermissions($request->permissions);

        // Связь с файлами
        if ($request->file) {
            $values->file()->sync($request->file);
        }


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

        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";


        // Роли
        $roles = DB::table('roles')->pluck('name', 'id');

        // Разрешения
        $permissions = DB::table('permissions')->pluck('name', 'id');

        // Картинка (получаем только картинки и для этого класса)
        $images = File::onlyImg()->whereType($this->info['model'])->pluck('path', 'id');

        // Добавить в начало коллекции
        $images->prepend(ltrim(config('add.imgDefault'), '/'), 0);

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });


        // Если есть связанные элементы, то получаем их
        /*$all = [];
        if ($this->relatedManyToManyEdit) {
            foreach ($this->relatedManyToManyEdit as $related) {
                if (!empty($related[0]) && !empty($related[2]) && !empty($related[3])) {
                    if (Schema::hasColumns($related[0], [$related[2], $related[3]])) {
                        $all[$related[0]] = DB::table($related[0])->pluck($related[3], $related[2]);
                    }
                }
            }
        }*/

        return view($view, compact('title', 'values', 'roles', 'permissions', 'images'));
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

        // Роль Admin может редактировать Admin
        if ($values->hasRole($this->adminRoleName) && !auth()->user()->hasRole($this->adminRoleName)) {
            return redirect()->back()->withErrors(__('s.admin_choose_admin'));
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|unique:{$this->info['table']},email,{$id}|max:255",
            'tel' => 'nullable|tel|max:255',
            'password' => 'nullable|string|min:6|same:password_confirmation',
        ];
        $request->validate($rules);
        $data = $request->all();


        
        // Сохраним прошлые данные
        LastData::saveData($id, $this->info['table']);


        // Если есть пароль, то он хэшируется
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }


        // Роли
        $values->syncRoles($request->roles);

        // Разрешения
        $values->syncPermissions($request->permissions);

        // Связь с файлами
        if ($request->file) {
            $values->file()->sync($request->file);
        }

        // Если есть связанные элементы, то синхронизируем их
        /*if ($this->relatedManyToManyEdit) {
            foreach ($this->relatedManyToManyEdit as $related) {
                if (!empty($related[0])) {

                    // Метод сохранения
                    $methodSave = $related[4] ?? 'sync';

                    // Удаляем связи многие ко многим
                    $values->{$related[0]}()->$methodSave($request->{$related[0]});
                }
            }
        }*/


        // Заполняем модель новыми данными
        $values->fill($data);

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

        // Удалить пользователя с ролью Admin может только Admin
        if ($values->hasRole($this->adminRoleName) && !auth()->user()->hasRole($this->adminRoleName)) {
            return redirect()->back()->withErrors(__('s.admin_choose_admin'));
        }


        // Удаляем прикреплённые разрешения
        $values->syncPermissions([]);

        // Удаляем прикреплённые роли
        $values->syncRoles([]);


        // Если есть связанные элементы, то удаляем их
        /*if ($this->relatedManyToManyEdit) {
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
        }*/


        // Удалить прикреплённые файлы из таблицы files и с сервера
        File::deleteFiles($values);

        // Удаляем элемент
        $values->delete();

        // Удалить все кэши
        cache()->flush();


        // Если пользователь удаляет сам себя
        if (auth()->user()->id == $id) {
            return redirect()->route('logout_get');
        }

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
    }
}
