<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $info;
    protected $active;
    protected $pagination;



    public function __construct()
    {
        $this->active = config('add.statuses')[1] ?? 'active';
        view()->share(['active' => $this->active]);
    }
}
