<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $info;
    protected $active;
    protected $pagination;



    public function __construct()
    {
        $active = $this->active = config('add.statuses')[1] ?? 'active';
        $this->pagination = config('add.pagination');


        // Только внутри этой конструкции работают некоторые методы
        $this->middleware(function ($request, $next) {

            // Сохраним Url, с которого перешёл пользователь из админки
            $this->saveAdminPreviousUrl();

            // Вручную аутентифицировать пользователя
            /*if (!auth()->check()) {
                $user = $this->userModel::find(1);
                auth()->login($user);
            }*/

            return $next($request);
        });

        view()->share(compact('active'));
    }


    /**
     *
     * @return array
     *
     * Получаем данные о текущем классе в массив $info, передаём в виды.
     *
     * @param array $params - передать в массив параметры, которые нужно изменить, например $params['table' => 'names'];
     */
    protected function info(array $params = [])
    {
        if (method_exists(request()->route(), 'getActionName')) {
            $controller = Str::before(class_basename(request()->route()->getActionName()), '@');
            $class = Str::before($controller, 'Controller');
            $action = request()->route()->getActionMethod();

            // Модель
            $model = config('add.models') . '\\' . $class;
            /*if (File::exists(config('add.models_path') . "/{$class}.php")) {
                $model = config('add.models') . '\\' . $class;
            }*/

            $view = Str::snake($class); // foo_bar
            $slug = Str::kebab($class); // foo-bar
            $route = request()->route()->getName();

            // Таблица
            $table = Str::plural($view); // к множественному числу
            /*if (!empty($model)) {
                $table = with(app()->make($model))->getTable();
            }*/
        }

        $info = [
            'controller' => $params['controller'] ?? $controller ?? null,
            'action' => $params['action'] ?? $action ?? null,
            'class' => $params['class'] ?? $class ?? null,
            'model' => $params['model'] ?? $model ?? null,
            'table' => $params['table'] ?? $table ?? null,
            'view' => $params['view'] ?? $view ?? null,
            'route' => $params['route'] ?? $route ?? null,
            'slug' => $params['slug'] ?? $slug ?? null,
        ];
        view()->share(compact('info'));
        return $info;
    }


    /**
     *
     * @return void
     *
     * Сохраняем в сессию страницу с которой пользователь перешёл из админки.
     */
    private function saveAdminPreviousUrl()
    {
        // Если пользователь авторизирован и url содержит админский префикс
        if (auth()->check() && Str::is('*' . config('add.admin') . '*', url()->previous())) {
            session()->put('back_link_admin', url()->previous());
        }
    }
}
