<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Setting extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at', 'updated_at'];



    // Возвращает массив названий настроек, название которых нельзя изменить из панели управления
    public static function keyNoEdit() {
        return [
            'name',
            'admin_email',
            'email',
            'tel',
            'date_format',
            'key_enter',
        ];
    }
}
