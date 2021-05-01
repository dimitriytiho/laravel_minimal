<?php

namespace App\Http\Controllers;

use App\Helpers\Func;
use App\Models\User;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->info = $this->info();
    }


    public function index()
    {
        // Название вида
        $view = $this->info['view'] . '.' . $this->info['action'];

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = __('s.home');
        $description = __('s.You_are_on_home');
        return view($view, compact('title', 'description'));
    }


    public function show($slug)
    {
        // Если пользователя есть разрешение к админскому классу, то будут показываться неактивные страницы
        if (auth()->check() && auth()->user()->hasRole(User::getRoleAdmin())) {

            $values = $this->info['model']::whereSlug($slug)
                ->firstOrFail();

        } else {

            $values = $this->info['model']::whereSlug($slug)
                ->active()
                ->firstOrFail();
        }


        // Подключает файл из resources/views/replace с название написаном в контенте ##!!!file_name (название файла file_name.blade.php).
        //$values->body = Func::inc($values->body);

        // Использовать скрипты в контенте, они будут перенесены вниз страницы.
        //$values->body = Func::downScripts($values->body);


        // Название вида
        $view = $this->info['view'] . '.' . $this->info['action'];

        // Проверка вида и info
        $this->viewExists($view, $this->info);

        $title = $values->title ?? null;
        $description = $values->description ?? null;
        return view($view, compact('title', 'description', 'values'));
    }
}
