<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    protected $fillable = [
        'user_id',
        'is_done',
        'reminder',
        'date',
        'name',
        'description'
    ];
    // // this make all columns as fillable
    // protected $guarded = [];
}
