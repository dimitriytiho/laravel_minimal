<?php

namespace App\Http\Controllers\Admin;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use App\Models\File as Files;
use Illuminate\Support\Facades\{DB, File, Hash};

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
            'roles',
            'ip',
            'id',
        ];

        // Параметры Get запроса
        $get = request()->query();
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;

        // Метод для поиска и сортировки запроса БД
        $values = $this->dbSort::getSearchSort($queryArr, $get, $this->info['table'], $this->info['model'], $this->info['view'], $this->pagination, null, null, 'roles');


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

        // Роли пользователей
        $roles = DB::table('roles')->pluck('name', 'id');

        // Картинка
        $images = Files::onlyImg()->pluck('path', 'id');

        // Добавить в начало коллекции
        $images->prepend(ltrim(config('add.imgDefault'), '/'), 0);


        $title = __('a.' . $this->info['action']);

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'roles', 'images'));
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
        $values = new $this->info['model']();

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

        // Проверка вида и info
        $this->viewExists($view, $this->info);


        // Роли пользователей
        $roles = DB::table('roles')->pluck('name', 'id');

        // Картинка
        $images = Files::onlyImg()->pluck('path', 'id');

        // Добавить в начало коллекции
        $images->prepend(ltrim(config('add.imgDefault'), '/'), 0);

        $title = __('a.' . $this->info['action']);

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values', 'roles', 'images'));
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
            'name' => 'required|string|max:255',
            'email' => "required|string|email|unique:{$this->info['table']},email,{$id}|max:255",
            'tel' => 'nullable|tel|max:255',
            'password' => 'nullable|string|min:6|same:password_confirmation',
        ];
        $request->validate($rules);
        $data = $request->all();

        // Если есть пароль, то он хэшируется
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }


        // Роли пользователя
        if ($request->roles) {
            $values->syncRoles($request->roles);
        }

        // Связь с файлами
        if ($request->file) {
            $values->file()->sync($request->file);
        }


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

        // Связь с файлами
        if ($values->file) {
            $values->file()->sync([]);

            // Удалить файл
            if (!empty($values->file->path) && File::exists(public_path($values->file->path))) {
                File::delete(public_path($values->file->path));
            }
        }

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
