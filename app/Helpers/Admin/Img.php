<?php


namespace App\Helpers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\ImageManagerStatic as Image;

class Img
{
    private $acceptedImages = [];

    private function __construct()
    {
        $this->acceptedImages = config('admin.acceptedImagesExt');
    }


    /**
     *
     * @return string
     *
     * Ресайз, сохранение и удаление прошлой картинки, возвращает новый путь картинки, от папки public.
     * $request - передать объкт Request.
     * $thisClass - название класса контроллера (например User).
     * $oldImage - если нужно удалить картинку, то передать путь от папки public, необязательный параметр.
     * $imgSize - размер картинки, по-умолчанию настройка admin.imgMaxSizeSM, необязательный параметр.
     * $imgName - name из input, по-умолчанию img необязательный параметр.
     * $crop - если нужно обрезать картинку, то передайте true, размеры обрезание в настройках admin.imgWidth и admin.imgHeight, необязательный параметр. Переменная $imgSize не учитывается.
     */
    public static function upload(Request $request, $thisClass, $oldImage = null, $imgSize = null, $imgName = 'img', $crop = null)
    {
        $img = $request->file($imgName);
        $imgExt = $img->extension();

        // Ресайз картинки
        $imgResize = Image::make($img->getRealPath());


        $width = (int)config('admin.imgWidth');
        $height = (int)config('admin.imgHeight');
        if ($crop && $imgResize->width() > $width || $crop && $imgResize->height() > $height) {


            // Обрезаем картинку в соответсвии с пропорциями
            /*if ($imgResize->width() > $imgResize->height()) {

                // Определяем меньшую сторону картинки
                $heightSide = (int)$imgResize->height();

                // Определяем во сколько раз меньше
                $rate = $imgResize->height() / $height;

                // Определяем размер другой стороны для обрезки
                $widthSide = (int)($width * $rate);

                // Определяем координаты
                $offsetX = (int)(($imgResize->width() - $widthSide) / 2);
                $offsetY = 0;

            } else {

                // Определяем меньшую сторону картинки
                $widthSide = (int)$imgResize->width();

                // Определяем во сколько раз меньше
                $rate = $imgResize->width() / $width;

                // Определяем размер другой стороны для обрезки
                $heightSide = (int)($height * $rate);

                // Определяем координаты
                $offsetX = 0;
                $offsetY = (int)(($imgResize->height() - $heightSide) / 2);
            }

            // Обрезаем картинку в соответсвии с пропорциями
            $imgResize->crop($widthSide, $heightSide, $offsetX, $offsetY);

            // Ресайз картинку к нужному размеру
            $imgResize->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });*/


            /*$width = $imgResize->width() > config('admin.imgWidth') ? config('admin.imgWidth') : $imgResize->width();
            $height = $imgResize->height() > config('admin.imgHeight') ? config('admin.imgHeight') : $imgResize->height();*/

            $width = $imgResize->width() > $width ? $width : $imgResize->width();
            $height = $imgResize->height() > $height ? $height : $imgResize->height();

            if ($imgResize->width() < $imgResize->height()) {
                $width = $height;
                $height = $width;
            }

            // Ресайз картинку к нужному размеру
            $imgResize->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            /*$canvas = Image::canvas(config('admin.imgWidth'), config('admin.imgHeight'));
            $canvas->insert($imgResize, 'center');*/

        } else {

            $imgSize = $imgSize ?: config('admin.imgMaxSizeSM');
            $width = $imgResize->width() > $imgSize ? $imgSize : $imgResize->width();
            $height = $imgResize->height() > $imgSize ? $imgSize : $imgResize->height();

            $imgResize->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }


        // Сохраняем картинку
        $ImgFolder = config("admin.img{$thisClass}") . '/' . date('Y_m');

        // Создадим папку если нет
        File::ensureDirectoryExists(public_path($ImgFolder));

        // Уникальное имя
        $imgPath = "{$ImgFolder}/" . uniqid() . ".{$imgExt}";

        // Сохраняем картинку
        $imgResize->save(public_path($imgPath));
        //empty($canvas) ? $imgResize->save(public_path($imgPath)) : $canvas->save(public_path($imgPath));

        // Удаляем картинку, которая была
        if ($oldImage && $oldImage !== config("admin.img{$thisClass}Default") && File::exists(public_path($oldImage))) {
            File::delete(public_path($oldImage));

            // Удалим картинку Webp
            $webp = self::getWebp($oldImage);
            if ($webp !== $oldImage) {
                File::delete(public_path($webp));
            }
        }

        return $imgPath;
    }


    // Возвращает разрешенные разрешения картинок строкой '.jpg, .jpeg, .png, .gif'
    public static function acceptedImagesExt()
    {
        $self = new self();
        return $self->acceptedImages ? '.' . implode(', .', $self->acceptedImages) : false;
    }


    // Поддерживает браузер картинки Webp, возвращает true или false.
    public static function supportWebp()
    {
        // https://github.com/pcosta94/laravel-check-webp-support/blob/master/src/helpers.php
        /*$httpAccept = request()->server('HTTP_USER_AGENT');
        return strpos($httpAccept, 'Trident') === false || strpos($httpAccept, 'Safari') === false;*/

        $httpAccept = request()->server('HTTP_ACCEPT');
        return strpos($httpAccept, 'image/webp') !== false;
    }


    /**
     *
     * @return string
     *
     * Возвращает название картинки Webp, если она есть, если её нет, то возвращает ''.
     * $imagePublicPath - путь с название обычной картинки.
     * Название картинки Webp должно быть одинаково с обычной картинкой.
     */
    public static function getWebp($imagePublicPath)
    {
        if ($imagePublicPath) {

            // Получаем данные картинки
            $info = self::imgInfo($imagePublicPath);

            // Путь к картинки Webp
            $webp = "{$info['folder_public_path']}/{$info['filename']}.webp";

            // Если есть Webp, то возвращаем её
            if (File::isFile(public_path($webp))) {
                return $webp;
            }
        }
        return '';
    }


    /**
     *
     * @return array
     *
     * Возвращает массив с данными картинки.
     * $imagePublicPath - название картинки, как в БД, например /img/product/tovar_1_10-03-2020_21-28.jpeg.
     */
    public static function imgInfo($imagePublicPath)
    {
        $info = pathinfo($imagePublicPath);
        return [
            'public_path' => $imagePublicPath,
            'full_path' => public_path($imagePublicPath),
            'folder_public_path' => $info['dirname'] ?? null,
            'folder_path' => empty($info['dirname']) ? null : public_path($info['dirname']),
            'basename' => $info['basename'] ?? null,
            'filename' => $info['filename'] ?? null,
            'ext' => $info['extension'] ?? null,
        ];
    }


    /**
     *
     * @return string
     *
     * Сделает копию картинки в формате Webp, возвращает путь к Webp картинке.
     * $imagePublicPath - название картинки, как в БД, например /img/product/tovar_1_10-03-2020_21-28.jpeg.
     */
    public static function copyWebp($imagePublicPath)
    {
        if ($imagePublicPath) {
            $info = self::imgInfo($imagePublicPath);
            $acceptedImagesExt = [
                'jpg',
                'jpeg',
                'png',
                'gif',
            ];

            if (in_array($info['ext'], $acceptedImagesExt)) {

                // Создать папку если нет
                File::ensureDirectoryExists($info['folder_path']);

                if (File::exists($info['full_path'])) {

                    // Определяем разрешение
                    if ($info['ext'] === 'jpeg' || $info['ext'] === 'jpg') {
                        $webp = @imagecreatefromjpeg($info['full_path']);

                    } elseif ($info['ext'] === 'png') {
                        $webp = @imagecreatefrompng($info['full_path']);

                    } elseif ($info['ext'] === 'gif') {
                        $webp = @imagecreatefromgif($info['full_path']);
                    }

                    // Копируем webp картинку
                    if (!empty($webp)) {
                        $webpName = "/{$info['filename']}.webp";
                        imagepalettetotruecolor($webp);
                        imagealphablending($webp, true);
                        imagesavealpha($webp, true);
                        imagewebp($webp, $info['folder_path'] . $webpName, config('admin.webpQuality'));
                        imagedestroy($webp);
                        return $info['folder_path'] . $webpName;
                    }
                }
            }
        }
        return null;
    }


    /**
     *
     * @return bool
     *
     * Удалим картинку с сервера, возвращает true или false.
     * $img - название картинки, как в БД, например /img/product/tovar_1_10-03-2020_21-28.jpeg.
     * $imgDefault - картинка по-умолчанию, если не надо её удалять, то передать, например config('admin.imgProductDefault'), необязательный параметр.
     */
    public static function deleteImg($img, $imgDefault = null)
    {
        // Проверим чтобы в пути картинки была папка для картинок
        if ($img && strpos($img, config('add.img')) !== false) {
            $imagesDefault = [
                config('add.img') . '/default/no_user.png',
                config('add.img') . '/default/no_image.jpg',
            ];
            $path = public_path($img);
            $ifDefault = $imgDefault === $img || in_array($img, $imagesDefault);

            if (!$ifDefault && File::isFile($path)) {

                // Удалим картинку Webp
                $webp = self::getWebp($img);
                if ($webp !== $img) {
                    File::delete(public_path($webp));
                }

                // Удалим обычную картинку
                File::delete($path);
                return true;
            }
        }
        return false;
    }


    /*
     * Удалим с сервера картинки галереи, принадлежащии одному элементу, к примеру товару, возвращает true или false.
     * $table - название таблице, в которой названия картинок.
     * $elementName - название элемента в таблице, к примеру product_id.
     * $elementId - id элемента, для которого картинки.
     */
    public static function deleteImgAll($table, $elementName, $elementId)
    {
        if ($table && $elementName && $elementId && Schema::hasTable($table) && Schema::hasColumn($table, $elementName)) {

            $images = DB::table($table)->where($elementName, (int)$elementId)->pluck('img');
            $images = $images->toArray();

            if ($images) {
                foreach ($images as $img) {
                    self::deleteImg($img);
                }
                return true;
            }
        }
        return false;
    }


    public static function exceptionsName($str) {
        if ($str) {
            $str = str_replace(' ', '_', $str);
            return str_replace([':', '-', '.'], '-', $str);
        }
        return false;
    }
}
