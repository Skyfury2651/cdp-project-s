<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LakeJob extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    public function lakes()
    {
        return $this->hasMany(Lake::class);
    }
}
