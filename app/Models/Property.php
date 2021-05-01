<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Property extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    // Связь один ко многим
    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}
