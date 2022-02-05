<?php

namespace App\Http\Controllers;

use App\Services\Info\InfoController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $info;
    protected $view;
    protected $active;
    protected $pagination;



    public function __construct()
    {
        $this->active = config('add.statuses')[1] ?? 'active';

        // Получаем данные о текущем классе
        $this->info = app()->make(InfoController::class);

        // Путь к видам, через указанный в настройках шаблон
        $this->view = config('add.template');

        view()->share([
            'active' => $this->active,
            'info' => $this->info,
            'view' => $this->view,
        ]);
    }
}
