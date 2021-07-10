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

            $snake = Str::snake($class);
            $kebab = Str::kebab($class);
            $view = Str::snake($action);
            $slug = Str::kebab($action);
            $route = request()->route()->getName();

            // Таблица
            $table = Str::plural($snake); // к множественному числу
            /*if (!empty($model)) {
                $table = with(app()->make($model))->getTable();
            }*/
        }

        $info = [
            'controller' => $params['controller'] ?? $controller ?? null, // FooDummyController
            'action' => $params['action'] ?? $action ?? null, // myDummy
            'class' => $params['class'] ?? $class ?? null, // FooDummy
            'snake' => $params['snake'] ?? $snake ?? null, // foo_dummy
            'kebab' => $params['kebab'] ?? $kebab ?? null, // foo-dummy
            'model' => $params['model'] ?? $model ?? null, // App\Models\FooDummy
            'table' => $params['table'] ?? $table ?? null, // foo_dummies
            'view' => $params['view'] ?? $view ?? null, // my_dummy
            'route' => $params['route'] ?? $route ?? null, // foo-dummy из routes/web.php из метода name()
            'slug' => $params['slug'] ?? $slug ?? null, // foo-dummy
        ];
        view()->share(compact('info'));
        return $info;
    }
}
