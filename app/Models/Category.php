<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'active'
    ];   
   
    public function competencias()
    {
        return $this->hasMany(Competencia::class);
    }

    use HasFactory;
}
