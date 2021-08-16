<?php

namespace App\Http\Controllers;

use App\Services\Info\InfoController;
use Illuminate\Http\Request;

class HomeController extends AppController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Название вида
        $view = $this->info->snake . '.' . $this->info->view;

        $title = __('s.account');
        return view($view, compact('title'));
    }
}
