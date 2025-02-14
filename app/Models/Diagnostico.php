<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnostico extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'diagnosticos';
    protected $fillable = [
        'name',
        'date_start',
        'date_finish',
        'diagnostico_360',
        'status_id',
        'company_id'
    ];

   

    // Valor por defecto estado al momento de crear un nuevo diagnostico.
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($diagnostico) {
            if (is_null($diagnostico->status_id)) {
                $status = DiagnosticoStatus::where('description', 'Borrador')->first();
                $diagnostico->status_id = $status['id'];
            }
        });
    }

    // FORMAT

    public function setDateStartAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['date_start'] = \Carbon\Carbon::parse($value);
        }
    }

    public function setDateFinishAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['date_finish'] = \Carbon\Carbon::parse($value);
        }
    }

    public function getUsersForDiagnosticoAttribute()
    {
        return User::whereIn('id', function($query) {
            $query->select('users.id')
                  ->from('diagnosticos')
                  ->join('diagnostico_sector', 'diagnostico_sector.diagnostico_id', '=', 'diagnosticos.id')
                  ->join('sectores', 'diagnostico_sector.sector_id', '=', 'sectores.id')
                  ->join('users', 'users.sector_id', '=', 'sectores.id')
                  ->where('diagnosticos.id', $this->id);
        })->distinct()->count();
    }

    public function getUsersHaveTestAttribute()
    {
        return User::whereIn('id', function($query) {
            $query->select('users.id')
                  ->from('test')
                  ->join('diagnostico_test', 'diagnostico_test.test_id', '=', 'test.id')
                  ->join('diagnosticos', 'diagnostico_test.diagnostico_id', '=', 'diagnosticos.id')
                  ->join('users', 'users.id', '=', 'test.user_id')
                  ->where('diagnosticos.id', $this->id);
        })->distinct()->count();
    }

    // RELACIONES
    public function status(){
        return $this->belongsTo(DiagnosticoStatus::class);
    }

    public function Company(){
        return $this->belongsTo(Companie::class, 'companies', 'company_id');
    }

    public function competencias(){
        return $this->belongsToMany(Competencia::class, 'competencia_diagnostico');
    }

    public function sectores()
    {
        return $this->belongsToMany(Sector::class, 'diagnostico_sector');
    }

    public function tests()
    {
        return $this->hasMany(DiagnosticoTest::class);
    }

    

}
