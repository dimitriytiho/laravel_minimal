<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Traits\ModelScopesTrait;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory, SoftDeletes, ModelScopesTrait, NodeTrait;


    protected $guarded = ['id', 'created_at', 'updated_at']; // Запрещается редактировать



    // Связь один ко многим внутри модели
    public function menus()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
