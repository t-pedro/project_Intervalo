<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticoTest extends Model
{
    protected  $table = 'diagnostico_test';
    use HasFactory;
    
    protected $fillable = [
        'diagnostico_id',
        'test_id',
        'user_id'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id');
    }

    public function diagnostico(){

        return $this->belongsTo(Diagnostico::class, 'diagnostico_id');
    }

    public function test(){

        return $this->belongsTo(Test::class, 'test_id');
    }
}
