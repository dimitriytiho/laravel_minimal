<?php


namespace App\Support\Admin;

use App\Models\File;
use Illuminate\Support\Str;

class Attachment
{
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
