<?php

use App\Models\Companie;
use App\Models\Competencia;
use Illuminate\Database\Eloquent\Model;

class CompanieCompetencia extends Model
{
    protected $table = 'companies_competencias';


    public function companies()
    {
        return $this->belongsToMany(Companie::class, 'companies_competencias', 'companie_id', 'competencia_id');
    }

    public function competencias()
{
    return $this->belongsToMany(Competencia::class, 'companies_competencias', 'competencia_id', 'companie_id');
}

}    

