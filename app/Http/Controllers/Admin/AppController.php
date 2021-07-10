<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Support\Admin\App;
use App\Support\{Func, UserLog};
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
    protected $belongRoute;

    // Для указание методов из моделей, для удобной реализации связей
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
        $adminRoleName = $this->adminRoleName = User::getRoleAdmin();
        $this->adminRoleId = cache()->rememberForever('admin_role_id', function () {
            return DB::table('roles')->whereName($this->adminRoleName)->value('id');
        });


        // Только внутри этой конструкции работают некоторые методы
        $this->middleware(function ($request, $next) {



            /*
             * Разрешения ролей пользователей.
             * Запрещение раздела, должена быть строка из сегмента URL, например http://site/page/create - значит page.
             * При переходе пользователя с этми запрещением будет 404 ошибка.
             */
            if (App::canUser($request->segment(2))) {
                Func::getError(auth()->user()->email . ' forbidden ' . $request->segment(2), __METHOD__, true, 'critical');
            }


            // Устанавливаем локаль
            Locale::setLocaleFromCookie($request);


            // Сохраняем в сессию страницу с которой пользователь перешёл в админку
            $previousUrl = url()->previous();
            $containAdmin = Str::is('*' . config('add.admin') . '*', $previousUrl);
            $containEnter = Str::is('*' . config('add.enter') . '*', $previousUrl);
            // Если url не содержит админский префикс
            if (!($containAdmin || $containEnter)) {
                session()->put('back_link_site', $previousUrl);
            }

            return $next($request);
        });

        /*view()->composer('vendor.laravel-log-viewer.log', function ($view) use ($pathPublic) {
            $view->with('pathPublic', $pathPublic);
        });*/


        // Хлебные крошки
        Breadcrumbs::for('home', function ($trail) {
            $trail->push(__('a.dashboard'), route('admin.main'));
        });



        // Кол-во элементов в некоторых таблицах, перечислить название таблиц
        $tables_count = [
            'pages',
            'users',
        ];
        $countTable = [];
        if ($tables_count) {
            foreach ($tables_count as $table) {
                if (Schema::hasTable($table)) {
                    $countTable[$table] = cache()->rememberForever("admin_{$table}_count", function () use ($table) {
                        if (Schema::hasColumn($table, 'deleted_at')) {
                            return DB::table($table)->whereNull('deleted_at')->count();
                        } else {
                            return DB::table($table)->count();
                        }
                    });
                }
            }
        }

        // Записываем все действия пользователей
        $this->userLog();

        view()->share(compact('namespaceSupport', 'viewPath', 'html', 'form', 'dbSort', 'countTable', 'isMobile', 'adminRoleName'));
    }



    // Записываем все действия пользователей
    private function userLog()
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
            UserLog::save('admin', $text);
        }
    }
}
