<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Attachment;
use App\Support\Admin\Img;
use App\Support\Func;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, File, Schema};
use Illuminate\Support\Str;

class FileController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info->table), route("{$this->viewPath}.{$this->info->kebab}.index"));
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Поиск. Массив гет ключей для поиска
        $queryArr = [
            'name',
            'path',
            'old_name',
            'type',
            'id',
        ];

        // Параметры Get запроса
        $get = request()->query();
        $col = $get['col'] ?? null;
        $cell = $get['cell'] ?? null;

        // Метод для поиска и сортировки запроса БД
        $values = $this->dbSort::getSearchSort($queryArr, $this->info->table, $this->info->model, $this->info->view, $this->pagination);


        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->info->view}";

        $title = __('a.' . $this->info->table);
        return view($view, compact('title', 'values', 'queryArr', 'col', 'cell'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->template}";

        $title = __('a.' . $this->info->action) . ' ' . Str::lower(__('a.' . $this->info->table));


        // Готовим массив для select
        $imagesExt = config('admin.images_ext');
        $exts = [];
        if ($imagesExt) {
            foreach($imagesExt as $key => $ext) {
                if (empty($ext[0])) {
                    $exts[$key] = ($ext[1] ?? null) . 'x' . ($ext[2] ?? null) . ' ' . Func::__($ext[3] ?? null, 'a');
                } else {
                    $exts[$key] = __('a.' . $ext[0]);
                }
            }
        }

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'exts'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = Attachment::upload();

        if (key_exists('success', $res)) {
            return redirect()
                ->route("admin.{$this->info->kebab}.index")
                ->with('success', $res['success']);

        } elseif (key_exists('error', $res)) {
            return back()->withErrors($res['error']);

        }
        return back()->withErrors(__('s.whoops'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info->model::findOrFail($id);

        if ($values->type) {
            $values->type = str_replace(config('add.models') . '\\', '', $values->type);
        }

        // Название вида
        $view = "{$this->viewPath}.{$this->info->snake}.{$this->template}";

        $title = __('a.' . $this->info->action) . ' ' . Str::lower(__('a.' . $this->info->table));

        // Хлебные крошки
        Breadcrumbs::for('action', function ($trail) use ($title) {
            $trail->parent('class');
            $trail->push($title);
        });

        return view($view, compact('title', 'values'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info->model::findOrFail($id);

        // Валидация
        $rules = [

        ];
        $request->validate($rules);
        $data = $request->all();

        if (!empty($data['type'])) {
            $data['type'] = config('add.models') . '\\' . request()->type;
        }

        // Заполняем модель новыми данными
        $values->fill($data);

        // Обновляем элемент
        $values->update();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info->kebab}.edit", $values->id)
            ->with('success', __('s.saved_successfully', ['id' => $values->id]));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Attachment::delete($id, false);
        if (key_exists('success', $res)) {
            return redirect()
                ->route("admin.{$this->info->kebab}.index")
                ->with('success', __('s.removed_successfully', ['id' => $id]));
        }
        return back()->withErrors(__('s.whoops'));
    }


    /*
     * Удаляет file и webp картинку, если она есть.
     *
     * Передать в ссылке get параметры:
     * &token=token - токен.
     * &id=1 - id файла.
     */
    public function delete(Request $request)
    {
        $res = Attachment::delete($request->id, $request->token);
        if (key_exists('success', $res)) {
            return back()->with('success', $res['success']);
        }
        return back()->withErrors(__('s.whoops'));
    }


    /*
     * Удаляет file и webp картинку, если она есть.
     *
     * Передать в ссылке get параметры:
     * &token=token - токен.
     * &table=users - название таблицы.
     * &id=1 - id файла в БД.
     */
    public function deleteImg(Request $request)
    {
        $table = $request->table;
        $id = $request->id;
        $imgDefault = config('add.imgDefault');
        if ($request->token === csrf_token() && Schema::hasColumn($table, 'img')) {
            $values = DB::table($table)->find($id);
            if ($values && $values->img !== $imgDefault) {

                // Удалить картинку
                Img::deleteImg($values->img);

                // Сохранить картинку по-умолчанию
                DB::table($table)->where('id', $id)->update(['img' => $imgDefault]);

                // Сообщение об успехе
                return back()->with('success', __('s.success_destroy'));
            }
        }

        // Сообщение что-то пошло не так
        return back()->withErrors(__('s.whoops'));
    }
}
