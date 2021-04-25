<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File as FileFacade;

class File extends Model
{
    use HasFactory;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    /*
     * Возвращает все элементы у которых разрешение картинок.
     *
     * Использование ->onlyImg()
     */
    public function scopeOnlyImg($query)
    {
        return $query->whereIn('ext', config('add.imgExt'));
    }


    /**
     *
     * @return bool
     *
     * Удалить прикреплённые файлы из таблицы files и с сервера.
     * $values - передать например $user, где есть связь file.
     */
    public static function deleteFiles($values)
    {
        if (isset($values->file)) {

            // Удаляем связи многие ко многим
            $values->file()->sync([]);

            // Удалить файл
            if ($values->file->count()) {
                foreach ($values->file as $file) {
                    if (!empty($file->path) && FileFacade::exists(public_path($file->path))) {
                        FileFacade::delete(public_path($file->path));

                        // Удалить Webp картинку
                        $webp = str_replace($values->ext, 'webp', $values->path);
                        if (FileFacade::exists(public_path($webp))) {
                            FileFacade::delete(public_path($webp));
                        }

                        // Удалить запись в таблице
                        $file->delete();
                    }
                }
                return true;
            }
        }
        return null;
    }
}
