<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Kalnoy\Nestedset\NodeTrait;
use App\Traits\ModelScopesTrait;

class Page extends Model
{
    use HasFactory, SoftDeletes, ModelScopesTrait, NodeTrait;


    protected $guarded = ['id', 'created_at', 'updated_at']; // Запрещается редактировать



    // Связь один ко многим внутри модели
    public function pages()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }


    // Связь многие ко многим для любых моделей
    public function properties()
    {
        return $this->morphToMany(Property::class, 'propertable', 'propertable')
            ->withTimestamps(); // Добавить, чтобы записывать в БД created_at updated_at;
    }
}
