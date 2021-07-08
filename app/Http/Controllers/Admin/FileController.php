<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Img;
use App\Support\Func;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, File};
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class FileController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        // Получаем данные о текущем классе в массив $info
        $this->info = $this->info();

        // Хлебные крошки
        Breadcrumbs::for('class', function ($trail) {
            $trail->parent('home');
            $trail->push(__('a.' . $this->info['table']), route("{$this->viewPath}.{$this->info['slug']}.index"));
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
        $values = $this->dbSort::getSearchSort($queryArr, $get, $this->info['table'], $this->info['model'], $this->info['view'], $this->pagination);


        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->info['action']}";

        $title = __('a.' . $this->info['table']);
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
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));


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
        // Сохраняем данные в переменную
        $exts = $request->ext ?: 0;
        $exts = config('admin.images_ext')[$exts] ?? null;
        $webp = $request->webp ? true : false;

        // Валидация данных
        $request->validate(['files' => 'required']);

        $dateDir = date('Y_m');
        $dir = config('add.file') . '/' . $dateDir;
        $dirFull = public_path($dir);

        // Создадим папку если нет
        File::ensureDirectoryExists($dirFull);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $mime = $file->getMimeType();
                $size = $file->getSize();
                $ext = $file->getClientOriginalExtension();
                $nameOld = $file->getClientOriginalName();
                $name = Str::lower(Str::random()) . '.' . $ext;
                $path = $dir . '/' . $name;


                // Если картинка
                $img = $ext === 'jpeg' || $ext === 'jpg' || $ext === 'png';

                if (empty($exts[0]) && $img) {
                    $width = empty($exts[1]) ? null : (int)$exts[1];
                    $height = empty($exts[2]) ? null : (int)$exts[2];
                    $crop = !empty($exts[3]) && $exts[3] === 'square';

                    // Ресайз картинки
                    $imgResize = Image::make($file->getRealPath());


                    // Ресайз в квадрат
                    if ($crop && $imgResize->width() > $width || $crop && $imgResize->height() > $height) {
                        /*$width = $imgResize->width() > $width ? $width : $imgResize->width();
                        $height = $imgResize->height() > $height ? $height : $imgResize->height();

                        if ($imgResize->width() < $imgResize->height()) {
                            $width = $height;
                            $height = $width;
                        }

                        // Ресайз картинку к нужному размеру
                        $imgResize->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });*/

                        $imgResize->fit($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });


                    // Ресайз с одной стороны
                    } else {

                        $width = $imgResize->width() > $width ? $width : $imgResize->width();
                        $height = $imgResize->height() > $height ? $height : $imgResize->height();

                        $imgResize->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }

                    // Сохраняем картинку
                    $imgResize->save(public_path($path));

                    // Скопировать картинку Webp
                    if ($webp) {
                        Img::copyWebp($path);
                    }


                // Если файл
                } else {

                    // Сохранить файл
                    $file->move($dirFull, $name);
                }


                // Сохранить в БД
                $data = $request->all();
                $data['name'] = $name;
                $data['path'] = $path;
                $data['ext'] = $ext;
                $data['mime_type'] = $mime;
                $data['size'] = File::size($dirFull . '/' . $name);
                $data['old_name'] = $nameOld;
                $this->info['model']::create($data);
            }

            // Сообщение об успехе
            return redirect()
                ->route("admin.{$this->info['slug']}.index")
                ->with('success', __('a.upload_success'));
        }

        // Сообщение что-то пошло не так
        return redirect()
            ->route("admin.{$this->info['slug']}.index")
            ->withErrors(__('s.whoops'));
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
        $values = $this->info['model']::findOrFail($id);

        // Название вида
        $view = "{$this->viewPath}.{$this->info['view']}.{$this->template}";

        $title = __('a.' . $this->info['action']) . ' ' . Str::lower(__('a.' . $this->info['table']));

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
        $values = $this->info['model']::findOrFail($id);

        // Валидация
        $rules = [

        ];
        $request->validate($rules);
        $data = $request->all();

        // Заполняем модель новыми данными
        $values->fill($data);

        // Обновляем элемент
        $values->update();

        // Удалить все кэши
        cache()->flush();

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.edit", $values->id)
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
        // Получаем элемент по id, если нет - будет ошибка
        $values = $this->info['model']::findOrFail($id);

        // Транзакция на 2 попытки
        DB::transaction(function () use ($id, $values) {

            // Удаляем связи
            DB::table('fileables')
                ->where('file_id', $id)
                ->delete();

            // Удаляем элемент
            $values->delete();
        }, 2);

        // Удалить файл
        if (File::exists(public_path($values->path))) {
            File::delete(public_path($values->path));

            // Удалить Webp картинку
            $webp = str_replace($values->ext, 'webp', $values->path);
            if (File::exists(public_path($webp))) {
                File::delete(public_path($webp));
            }
        }

        // Сообщение об успехе
        return redirect()
            ->route("admin.{$this->info['slug']}.index")
            ->with('success', __('s.removed_successfully', ['id' => $values->id]));
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
        $id = (int)$request->id;
        if ($request->token && $request->token === csrf_token() && $id) {

            // Получаем элемент по id, если нет - будет ошибка
            $values = $this->info['model']::findOrFail($id);

            // Транзакция на 2 попытки
            DB::transaction(function () use ($id, $values) {

                // Удаляем связи
                DB::table('fileables')
                    ->where('file_id', $id)
                    ->delete();

                // Удаляем элемент
                $values->delete();
            }, 2);

            // Удалить файл
            if (File::exists(public_path($values->path))) {
                File::delete(public_path($values->path));

                // Удалить Webp картинку
                $webp = str_replace($values->ext, 'webp', $values->path);
                if (File::exists(public_path($webp))) {
                    File::delete(public_path($webp));
                }
            }

            // Сообщение об успехе
            return redirect()
                ->back()
                ->with('success', __('s.success_destroy'));
        }

        // Сообщение что-то пошло не так
        return redirect()
            ->back()
            ->withErrors(__('s.whoops'));
    }
}
