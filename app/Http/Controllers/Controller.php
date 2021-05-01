<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
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


    /*
     * Получаем данные о текущем классе в массив $info
     * $modelClass - если модель используется отличная от названия контроллера, то передать её класс, например \App\Models\Page::class.
     */
    protected function info($modelClass = null)
    {
        if (method_exists(request()->route(), 'getActionName')) {
            $controller = Str::before(class_basename(request()->route()->getActionName()), '@');
            $class = Str::before($controller, 'Controller');
            $action = request()->route()->getActionMethod();

            // Модель
            if ($modelClass) {
                $model = $modelClass;
            } elseif (File::exists(config('add.models_path') . "/{$class}.php")) {
                $model = config('add.models') . '\\' . $class;
            }

            // Таблица
            if (!empty($model)) {
                $table = with(new $model)->getTable();
            }

            $view = Str::snake($class); // foo_bar
            $slug = Str::kebab($class); // foo-bar
            $route = request()->route()->getName();
        }

        $info = [
            'controller' => $controller ?? null,
            'action' => $action ?? null,
            'class' => $class ?? null,
            'model' => $model ?? null,
            'table' => $table ?? null,
            'view' => $view ?? null,
            'route' => $route ?? null,
            'slug' => $slug ?? null,
        ];
        view()->share(compact('info'));
        return $info;
    }


    // Проверка вида и info
    protected function viewExists($viewName, $info)
    {
        if (empty($info['controller']) || !view()->exists($viewName)) {
            $message = "View $viewName not found.";
            logger()->critical($message);
            abort('404', $message);
        }
    }


    // Сохраняем в сессию страницу с которой пользователь перешёл из админки
    private function saveAdminPreviousUrl()
    {
        if (auth()->check()) {

            // Если url содержит админский префикс
            if (Str::is('*' . config('add.admin') . '*', url()->previous())) {
                session()->put('back_link_admin', url()->previous());
            }
        }
    }
}
