<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestStatus extends Model
{
    protected  $table = 'test_status';
    use HasFactory;

    protected $fillable = [
        'description'
    ];
}
