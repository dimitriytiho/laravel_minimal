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
}
