<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Commands;
use App\Support\Seo;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdditionallyController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['view']), route("{$this->viewPath}.{$this->info['slug']}.index"));
        });
    }


    public function index(Request $request) {

        // Работа с Кэшем
        $cache = $request->query('cache');
        if ($cache) {
            switch ($cache) {
                case 'db':
                    cache()->flush();
                    session()->flash('success', __('a.cache_deleted'));
                    return redirect()->route("admin.{$this->info['slug']}");

                case 'views':
                    $res = Commands::getCommand('php artisan view:clear');
                    $res ? session()->flash('success', $res) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['slug']}");

                case 'routes':
                    $res1 = Commands::getCommand('php artisan route:clear');
                    $res1 ? session()->flash('success', $res1) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['slug']}");

                case 'config':
                    $res1 = Commands::getCommand('php artisan config:clear');
                    $res1 ? session()->flash('success', $res1) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['slug']}");
            }
        }


        // Обновление сайта Seo
        $upload = $request->query('upload');
        if ($upload === 'run') {
            Seo::getUpload();

            return redirect()
                ->route("admin.{$this->info['slug']}")
                ->with('success', __('a.completed_successfully'));
        }


        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->info['action']}";

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('a.' . $this->info['view']);
        return view($view, compact('title'));
    }
}
