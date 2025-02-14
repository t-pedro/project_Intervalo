<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capsule extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url',
        'image'
    ];

    use HasFactory;
    use SoftDeletes;

    public function Competencias()
    {
        return $this->belongsToMany(Competencia::class);
    }
}
