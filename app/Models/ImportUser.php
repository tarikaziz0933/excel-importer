<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportUser extends Model
{

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}