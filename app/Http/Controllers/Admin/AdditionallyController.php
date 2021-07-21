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
            $trail->push(__('a.' . $this->info['snake']), route("{$this->viewPath}.{$this->info['kebab']}"));
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // Работа с Кэшем
        $cache = $request->query('cache');
        if ($cache) {
            switch ($cache) {
                case 'db':
                    cache()->flush();
                    session()->flash('success', __('a.cache_deleted'));
                    return redirect()->route("admin.{$this->info['kebab']}");

                case 'views':
                    $res = Commands::getCommand('php artisan view:clear');
                    $res ? session()->flash('success', $res) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['kebab']}");

                case 'routes':
                    $res1 = Commands::getCommand('php artisan route:clear');
                    $res1 ? session()->flash('success', $res1) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['kebab']}");

                case 'config':
                    $res1 = Commands::getCommand('php artisan config:clear');
                    $res1 ? session()->flash('success', $res1) : session()->flash('errors', __('s.something_went_wrong'));
                    return redirect()->route("admin.{$this->info['kebab']}");
            }
        }


        // Обновление сайта Seo
        $upload = $request->query('upload');
        if ($upload === 'run') {
            Seo::getUpload();

            return redirect()
                ->route("admin.{$this->info['kebab']}")
                ->with('success', __('a.completed_successfully'));
        }


        // Название вида
        $view = "{$this->viewPath}.{$this->info['snake']}.{$this->info['view']}";

        $title = __('a.' . $this->info['snake']);
        return view($view, compact('title'));
    }
}
