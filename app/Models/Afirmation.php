<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afirmation extends Model
{
    protected $fillable = [
        'text',
        'ponderacion'
    ];

    use HasFactory;
    use SoftDeletes;

    public function Competencias()
    {
        return $this->belongsToMany(Competencia::class);
    }

}
