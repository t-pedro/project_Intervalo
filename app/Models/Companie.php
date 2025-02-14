<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companie extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'companies';
    protected $fillable = [
        'description',
        'address',
        'contact_name',
        'contact_email',
        'contact_phone',
        'active',
        'show_competencias'
    ];

    protected $casts = [
        'show_competencias' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    }  

    public function competencias(){
        return $this->belongsToMany(Competencia::class, 'companies_competencias');
    }

    public function diagnosticos(){
        return $this->belongsTo(Diagnostico::class);
    }

    public function sectores(){
        return $this->belongsTo(Sector::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'companies_users');
    }

}
