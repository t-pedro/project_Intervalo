<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Models\Companie;
use App\Models\Competencia;
use App\Models\Diagnostico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()){
            if(Auth::user()->roles[0]->id == 1){ // Si el usuario es administrador retorno todas las competencias. 
                $competencias = Competencia::all();
                $diagnosticos = Diagnostico::where('status_id', 2)
                        ->where('date_start', '<=', Carbon::now())
                        ->where(function ($query) {
                            $query->where('date_finish', null)
                                ->orWhere('date_finish', '>=', Carbon::now());
                        })->with('competencias')
                    ->get();
                $users = User::all();
                $show_competencias = true;
            }else{
                $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();
                $show_competencias = $companie->show_competencias;

                $competencias = Competencia::whereIn('id', $companie->competencias->pluck('id')->toArray())->get();
                if(Auth::user()->roles[0]->id == 2){
                    $diagnosticos = Diagnostico::where('company_id', Auth::user()->companies[0]->id)
                        ->where('status_id', 2)
                        ->where('date_start', '<=', Carbon::now())
                        ->where(function ($query) {
                            $query->where('date_finish', null)
                                ->orWhere('date_finish', '>=', Carbon::now());
                        })->with('competencias')
                    ->get(); //companies_users
                    $companyId = Auth::user()->companies[0]->id;

                    $users = User::whereIn('id', function ($query) use ($companyId) {
                        $query->select('users.id')
                              ->from('users')
                              ->join('companies_users', 'users.id', '=', 'companies_users.user_id')
                              ->where('companies_users.companie_id', $companyId);
                    })->get();
                }else{
                    $sector_id = Auth::user()->sector_id;
                    $diagnosticos = Diagnostico::where('company_id', Auth::user()->companies[0]->id)
                        ->whereIn('id', function ($sub) use($sector_id ) {
                            $sub->selectRaw('diagnosticos.id')
                                ->from('diagnosticos')
                                ->join('diagnostico_sector', 'diagnosticos.id', '=', 'diagnostico_sector.diagnostico_id')
                                ->where('diagnostico_sector.sector_id', $sector_id);
                        })
                        ->where('status_id', 2)
                        ->where('date_start', '<=', Carbon::now())
                        ->where(function ($query) {
                            $query->where('date_finish', null)
                                ->orWhere('date_finish', '>=', Carbon::now());
                        })->with('competencias')
                    ->get(); //companies_users

                    $companyId = Auth::user()->companies[0]->id;

                    $users = User::whereIn('id', function ($query) use ($companyId) {
                        $query->select('users.id')
                              ->from('users')
                              ->join('companies_users', 'users.id', '=', 'companies_users.user_id')
                              ->where('companies_users.companie_id', $companyId);
                    })->get();
                }
            }
            return  Inertia::render('Web/Home',['competencias' => $competencias, 'diagnosticos' => $diagnosticos, 'users' => $users, 'show_competencias' => $show_competencias ]);
        }else{
            return  Inertia::render('Web/Home',['competencias' => Competencia::all(), 'show_competencias' => true ]);
        }
    }

}