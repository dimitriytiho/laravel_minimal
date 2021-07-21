<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class ActivityLogController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['snake']), route("{$this->viewPath}.{$this->info['kebab']}"));
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = null;
        $values = null;
        $tags = config('add.user_log_tags');

        // Кэшируем все роли
        $roles = cache()->rememberForever('roles_all', function () {
            return Role::all()->pluck('name');
        });

        if (!empty($roles[0])) {

            // Если роль сохранена в сессии и она есть в объекте ролей, то возьмём, иначе первая роль
            $role = $roles->contains(session('log_role')) ? session('log_role') : $roles[0];

            // Кэшируем пользователей определённой роли
            $users = cache()->rememberForever('users_role_' . $role, function () use ($role) {
                $users = User::role($role)->select('id', 'name', 'email')->get()->keyBy('id');
                return $users->map(function ($el) {
                    return $el->name . ' ' . $el->email;
                });
            });
        }


        if ($users) {

            // Если пользователь сохранён в сессии и он есть в объекте пользователей для этой роли, то возьмём его, иначе первый пользователь
            $user = $users->keys()->contains(session('log_user')) ? session('log_user') : $users->keys()->first();

            // Получаем логи, начинаем формировать запрос
            $values = Activity::where('causer_type', 'App\Models\User')->where('causer_id', $user);

            // Tag
            $tag = session('log_tag');
            if ($tag && in_array($tag, $tags)) {
                $values = $values->where('log_name', $tag);
            }

            // Получаем логи
            $values = $values->paginate(session('pagination') ?: $this->pagination);
        }


        // Добавляем в начало
        if ($tags) {
            array_unshift($tags, 'Tags');
        }

        // Название вида
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->info['view']}";

        $title = __('a.' . $this->info['snake']);
        return view($view, compact('title', 'roles', 'users', 'tags', 'values'));
    }
}
