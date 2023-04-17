<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LakeAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'header', 'value'];
}
