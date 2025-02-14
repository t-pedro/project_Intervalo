<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    protected  $table = 'roles';
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'id',
        'name',
        'description'
    ];
 
    public function users(){
        return $this->belongsToMany(User::class, 'roles_users');
    }
}
