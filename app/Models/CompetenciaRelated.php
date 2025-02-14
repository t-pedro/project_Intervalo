<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetenciaRelated extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected  $table = 'competencias_related';
    
    protected $fillable = [
        'competencia_id',
        'relate_id',
        'feedback_approve',
        'feedback_disapprove'
    ];

    public function competencia(){

        return $this->belongsTo(Competencia::class, 'competencia_id');
    }

    public function competencia_relate(){

        return $this->belongsTo(Competencia::class, 'relate_id');
    }

    public function test()
    {
        return $this->hasMany(Test::class);
    }

}
