<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticoStatus extends Model
{
    protected  $table = 'diagnostico_status';
    use HasFactory;

    protected $fillable = [
        'description'
    ];

}
