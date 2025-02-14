<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Competencia extends Model
{
    protected $fillable = [
        'competencia',
        'definicion',
        'comportamiento',
        'resume',
        'category_id'
    ];

    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        // Aplicar un scope global para ordenar por 'created_at'
        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('competencia', 'asc');
        });
    }


    // Getter para verificar si existe una relación con el mismo ID
    public function getHasSelfRelationAttribute()
    {
        return $this->competencias_relate->contains('relate_id', $this->id);
    }


    public function afirmations()
    {
        return $this->belongsToMany(Afirmation::class);
    } 

    public function Capsules()
    {
        return $this->belongsToMany(Capsule::class);
    }
    
    public function category(){

        return $this->belongTo(Category::class);
    }

    public function competencias_relate()
    {
        return $this->hasMany(CompetenciaRelated::class);
    }

    public function companies(){
        return $this->belongsToMany(Companie::class, 'companies_competencias');
    }

    
}
