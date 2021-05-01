<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class MenuGroup extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at', 'updated_at']; // Запрещается редактировать



    // Связь один к многим
    public function menus()
    {
        return $this->hasMany(Menu::class, 'belong_id');
    }
}
