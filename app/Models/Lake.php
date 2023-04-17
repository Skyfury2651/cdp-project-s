<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Lake extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function attributes()
    {
        return $this->hasMany(LakeAttribute::class);
    }
}
