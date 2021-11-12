<?php


namespace App\Support\Admin;

use App\Models\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File as FileSupport;

class Attachment
{
    /**
     * @return array
     *
     * Возвращает в массиве ключи: error или success.
     * Сохраняет файлы на сервер в папку /public/file/год_месяц.
     * Максимальный размер файла 4мб.
     * Картинка ресайз до размеров config.admin.images_ext.
     *
     * @param bool $webp - если нужно копировать картинки в формате webp, по-умолчанию копируем.
     */
    public static function upload($webp = true)
    {
        // Сохраняем данные в переменную
        $exts = request()->ext ?: 0;
        $exts = config('admin.images_ext')[$exts] ?? null;
        //$webp = $request->webp ? true : false;

        // Валидация данных
        $rules = [
            'files' => 'required',
        ];
        request()->validate($rules);

        $dateDir = date('Y_m');
        $dir = config('add.file') . '/' . $dateDir;
        $dirFull = public_path($dir);

        // Создадим папку если нет
        FileSupport::ensureDirectoryExists($dirFull);

        foreach (request()->file('files') as $key => $file) {
            $size = $file->getSize();

            // Сообщение о большом размере файла 2097152
            if (!$size || $size >= 4194304) {
                return ['error' => __('a.max_size_files_continue', ['size' => 4000])];
            }

            $mime = $file->getMimeType();
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
            $data = [
                'user_id' => auth()->check() ? auth()->user()->id : null,
                'type' => request()->type ? config('add.models') . '\\' . request()->type : null,
                'name' => $name,
                'path' => $path,
                'ext' => $ext,
                'mime_type' => $mime,
                'size' => FileSupport::size($dirFull . '/' . $name),
                'old_name' => $nameOld,
            ];
            File::create($data);
        }
        return ['success' => __('a.upload_success')];
    }


    /**
     *
     * @return string
     *
     * Возвращает html preview файла, в зависимости от разрешения файла.
     *
     * @param object $attachment - объект файла класса File обязательно.
     */
    public static function previewFile(File $attachment)
    {
        if (!empty($attachment->id)) {

            $arr = [
                'pdf',
                'jpg',
                'jpeg',
                'png',
                'svg',
            ];

            $ext = pathinfo($attachment->name)['extension'] ?? null;
            $ext = Str::lower($ext);

            // Если картинка, то preview img
            if (in_array($ext, $arr)) {

                return self::previewImgPdf($attachment, $ext);

            // Остальные варианты иконка
            } else {

                return self::previewIcon($attachment, $ext);
            }
        }
        return null;
    }


    /**
     *
     * @return string
     *
     * Возвращает html.
     * @param object $attachment - объект файла класса File обязательно.
     * @param string $ext - разрешение файла.
     */
    public static function previewImgPdf(File $attachment, $ext)
    {
        if ($ext) {
            $url = asset($attachment->path);

            // Для pdf файла
            if ($ext === 'pdf') {

                return "<embed src='{$url}' type='{$attachment->mime_type}' width='50' height='71' frameborder='0'>";

            // Для картинок
            } else {

                return "<img src='{$url}' class='img-size-64' alt='{$attachment->name}'>";
            }
        }
        return "<i class='far fa-file-alt fa-3x'></i>";
    }


    /**
     *
     * @return string
     *
     * Возвращает html иконки Fontawesome.
     *
     * @param object $attachment - объект файла класса File обязательно.
     * @param string $ext - разрешение файла.
     */
    public static function previewIcon(File $attachment, $ext)
    {
        $icon = 'fa-file-alt'; // Default icon
        if ($ext) {

            $arr = [
                'pdf' => 'fa-file-pdf',
                'doc' => 'fa-file-word',
                'docx' => 'fa-file-word',
                'xls' => 'fa-file-excel',
                'xlsx' => 'fa-file-excel',
                'ppt' => 'fa-file-powerpoint',
                'pptx' => 'fa-file-powerpoint',
                'zip' => 'fa-file-archive',
                'rar' => 'fa-file-archive',
                'tif' => 'fa-file-image',
                'tiff' => 'fa-file-image',
                'bmp' => 'fa-file-image',
                'gif' => 'fa-file-image',
                'png' => 'fa-file-image',
                'jpeg' => 'fa-file-image',
                'jpg' => 'fa-file-image',
            ];

            if (key_exists($ext, $arr)) {
                $icon = $arr[$ext];
            }
        }
        return "<i class='far {$icon} fa-3x'></i>";
    }
}
