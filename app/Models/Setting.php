<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Setting extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id', 'created_at', 'updated_at'];
}
