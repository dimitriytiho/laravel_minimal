<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
