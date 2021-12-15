<?php

namespace App\Http\Controllers\Admin;

use App\Services\Auth\Role;
use App\Support\UserLog;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;
use App\Support\Admin\Locale;
use Illuminate\Pagination\Paginator;

class AppController extends Controller
{
    protected $namespaceModels;
    protected $namespaceSupport;
    protected $html;
    protected $form;
    protected $viewPath;
    protected $template;
    protected $dbSort;
    protected $pagination;
    protected $paginationQty;
    protected $adminRoleName;
    protected $adminRoleId;

    // Связанный родитель
    protected $belongTable;
    protected $belongItem;
    protected $belongRoute;

    // Для указания методов из моделей, для удобной реализации связей
    protected $relatedManyToManyEdit = [];
    protected $relatedManyToManyDelete = [];



    public function __construct(Request $request)
    {
        parent::__construct();


        // Определить мобильную версию
        $detect = app()->make('Detection\MobileDetect');
        $isMobile = $detect->isMobile();


        // Пагинация Bootstrap
        Paginator::useBootstrap();


        $this->namespaceModels = config('add.models');
        $namespaceSupport = $this->namespaceSupport = config('add.support') . '\\Admin';
        $viewPath = $this->viewPath = 'admin';

        $html = $this->html = "{$this->namespaceSupport}\\Html";
        $form = $this->form = config('add.services') . '\\Form\\FormAdmin';

        $this->template = 'general';
        $dbSort = $this->dbSort = "{$this->namespaceSupport}\\DbSort";
        $this->pagination = config('admin.pagination_default');
        $this->paginationQty = config('admin.pagination');
        $adminRoleName = $this->adminRoleName = Role::ADMIN_NAME;
        $this->adminRoleId = cache()->rememberForever('admin_role_id', function () {
            return DB::table('roles')->whereName($this->adminRoleName)->value('id');
        });


        // Только внутри этой конструкции работают методы авторизированного пользователя
        $this->middleware(function ($request, $next) {

            // Записываем все действия пользователей
            $this->userLog(auth()->user());

            // Устанавливаем локаль
            Locale::setLocaleFromCookie($request);

            // Сохраняем в сессию страницу с которой пользователь перешёл в админ панель
            $this->savePreviousUrl();

            return $next($request);
        });

        /*view()->composer('vendor.laravel-log-viewer.log', function ($view) use ($pathPublic) {
            $view->with('pathPublic', $pathPublic);
        });*/


        // Хлебные крошки
        Breadcrumbs::for('home', function ($trail) {
            $trail->push(__('a.dashboard'), route('admin.main'));
        });



        // Кол-во элементов в таблицах
        $countTable = $this->tablesCount();


        view()->share(compact('namespaceSupport', 'viewPath', 'html', 'form', 'dbSort', 'countTable', 'isMobile', 'adminRoleName'));
    }




    // Записываем все действия пользователей
    private function userLog($user)
    {
        // Методы, которые записываем
        $methodsLog = [
            'store',
            'update',
            'destroy',
        ];

        if (method_exists(request()->route(), 'getActionMethod') && in_array(request()->route()->getActionMethod(), $methodsLog)) {

            $text = request()->getMethod();

            if (method_exists(request()->route(), 'parameters')) {
                $text .= ' ' . current(request()->route()->parameters());
            }

            if (method_exists(request()->route(), 'getActionName')) {
                $text .= ' ' . request()->route()->getActionName();
            }
            UserLog::save('admin', $text, $user);
        }
    }


    // Кол-во элементов в таблицах
    private function tablesCount()
    {
        // Таблицы, которые не кэшируем
        $tablesCount = [
            'forms',
        ];
        // Таблицы, которые кэшируем
        $tablesCountCache = [
            'pages',
            'users',
        ];
        if ($tablesCountCache) {
            $countTable = cache()->rememberForever('admin_tables_count', function () use ($tablesCountCache) {
                $res = [];
                foreach ($tablesCountCache as $table) {
                    if (Schema::hasTable($table)) {
                        $res[$table] = DB::table($table);
                        if (Schema::hasColumn($table, 'deleted_at')) {
                            $res[$table] = $res[$table]->whereNull('deleted_at');
                        }
                        $res[$table] = $res[$table]->count();
                    }
                }
                return $res;
            });
        }
        if ($tablesCount) {
            foreach ($tablesCount as $table) {
                if (Schema::hasTable($table)) {
                    $countTable[$table] = DB::table($table);
                    if (Schema::hasColumn($table, 'deleted_at')) {
                        $countTable[$table] = $countTable[$table]->whereNull('deleted_at');
                    }
                    $countTable[$table] = $countTable[$table]->count();
                }
            }
        }
        /*if (Schema::hasTable('orders')) {
            $countTable['orderNew'] = DB::table('orders')
                ->whereNull('deleted_at')
                ->whereStatus(config('shop.order_statuses')[0] ?? 'new')
                ->count();
        }*/
        return $countTable ?? [];
    }


    /**
     *
     * @return void
     *
     * Сохраняем в сессию страницу с которой пользователь перешёл в админ панель.
     */
    private function savePreviousUrl()
    {
        $previousUrl = url()->previous();
        $containAdmin = Str::is('*' . config('add.admin') . '*', $previousUrl);
        $containEnter = Str::is('*' . config('add.enter') . '*', $previousUrl);
        // Если url не содержит админский префикс
        if (!($containAdmin || $containEnter)) {
            session()->put('back_link_site', $previousUrl);
        }
    }
}
