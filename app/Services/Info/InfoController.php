<?php

namespace App\Services\Info;

use Illuminate\Support\Str;

class InfoController
{
    public $controller; // FooDummyController
    public $class; // FooDummy
    public $action; // myDummy
    public $snake; // foo_dummy
    public $kebab; // foo-dummy
    public $model; // App\Models\FooDummy
    public $table; // foo_dummies
    public $view; // my_dummy
    public $route; // foo-dummy из routes/web.php из метода name()
    public $slug; // foo-dummy


    public function __construct()
    {
        $this->get();
    }


    public function __get($name)
    {
        return null;
    }


    private function get()
    {
        if (method_exists(request()->route(), 'getActionName')) {
            $this->controller = Str::before(class_basename(request()->route()->getActionName()), '@');
            $this->class = Str::before($this->controller, 'Controller');
            $this->action = request()->route()->getActionMethod();

            // Модель
            $this->model = config('add.models') . '\\' . $this->class;
            /*if (File::exists(config('add.models_path') . "/{$this->class}.php")) {
                $this->model = config('add.models') . '\\' . $this->class;
            }*/

            $this->snake = Str::snake($this->class);
            $this->kebab = Str::kebab($this->class);
            $this->view = Str::snake($this->action);
            $this->slug = Str::kebab($this->action);
            $this->route = request()->route()->getName();

            // Таблица
            $this->table = Str::plural($this->snake); // к множественному числу
            /*if (!empty($this->model)) {
                $this->table = with(app()->make($this->model))->getTable();
            }*/
        }
    }
}
