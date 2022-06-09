<?php

namespace App\Http\Controllers;

use App\Support\Facades\Registry;
use App\Support\Func;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SearchController extends AppController
{
    // Добавлять в массиве модели для поиска
    private $searchConfig = [
        [ // Первая модель основная по-умолчанию, она обязательна
            'table' => 'products',
            'model' => 'Product',
            'route' => 'product',
        ],
        [
            'table' => 'services',
            'model' => 'Service',
            'route' => 'service',
        ],
    ];
    // Колонки, которые необходимо получать из всех моделей, к ним будет добавлена колонка route
    private $searchColumns = [
        'id',
        'title',
        'slug',
        'price',
        'img',
        'sort'
    ];
    private $limitAjax = 72;



    public function __construct(Request $request)
    {
        parent::__construct();
    }


    public function index(Request $request)
    {
        $s = strip_tags($request->get('s'));
        $values = $this->elementsObject($s);
        $values = $values->paginate($this->pagination);


        $title = __('a.search');

        // Хлебные крошки
        Breadcrumbs::for('app', function ($trail) use ($title) {
            $trail->parent('home');
            $trail->push($title, route('page', route('search')));
        });

        return view($this->view . '.page.' . $this->info->snake, compact('title', 'values'));
    }


    public function js(Request $request)
    {
        if ($request->ajax()) {
            $s = strip_tags($request->get('s'));
            if ($s) {

                $values = $this->elementsObject($s);
                $values = $values->limit($this->limitAjax)->get();

                if ($values->count()) {
                    $res = view("{$this->view}.inc.search_item", compact('values'))->render();
                }

                return $res ?? '';
            }
            die;
        }
        Func::getError('Request', __METHOD__);
    }


    private function elementsObject($s)
    {
        $values = null;
        Registry::set('search_query', $s);
        if ($s && $this->searchConfig && $this->searchColumns) {

            foreach ($this->searchConfig as $key => $item) {
                $modelName = $item['model'];
                $model = config('add.models') . '\\' . $modelName;
                if ($key && Schema::hasColumns($item['table'], $this->searchColumns)) {
                    $$modelName = $model::select($this->searchColumns)
                        ->addSelect(DB::raw("'{$item['route']}' as route"))
                        ->where('title', 'LIKE', "%{$s}%");
                } else {
                    $main = $model::select($this->searchColumns)
                        ->addSelect(DB::raw("'{$item['route']}' as route"))
                        ->where('title', 'LIKE', "%{$s}%");
                }
            }
            foreach ($this->searchConfig as $key => $item) {
                if (!$key) {
                    $values = $main->union($$modelName);
                }
            }
            $values = $values->whereStatus($this->active)->order();
        }
        return $values;
    }
}
