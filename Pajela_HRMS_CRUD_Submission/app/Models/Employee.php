<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'FN',
        'MN',
        'LN',
        'gender',
        'hire_date',
        'status'
    ];
}
