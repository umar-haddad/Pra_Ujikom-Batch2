<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Count extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'jenis',
        'angka1',
        'angka2',
        'hasil'
    ];
}
