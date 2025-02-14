<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $table = 'test_detail';
    
    protected $fillable = [
        'test_id',
        'competencia_related_id',
        'score'
    ];

    public function test(){

        return $this->belongsTo(Test::class, 'test_id');
    }

    public function competencia_related(){

        return $this->belongsTo(CompetenciaRelated::class, 'competencia_related_id');
    }

    public function getAvgScoreAttribute(){
        return $this->score / $this->test->test_detail->count();
    }
}
