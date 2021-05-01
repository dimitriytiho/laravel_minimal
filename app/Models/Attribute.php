<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Attribute extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function properties()
    {
        return $this->belongsTo(Property::class);
    }
}
