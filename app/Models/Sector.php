<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    protected  $table = 'sectores';
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'id',
        'name',
        'active',
        'company_id'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    } 
 
    public function company(){
        return $this->belongTo(Companie::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function diagnosticos(){
        return $this->belongsToMany(Diagnostico::class);
    }

}
