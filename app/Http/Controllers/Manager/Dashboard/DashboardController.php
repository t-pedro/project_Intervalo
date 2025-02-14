<?php

namespace App\Http\Controllers\Manager\Dashboard;

// use App\Models\Persona;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Models\Companie;
use App\Models\Competencia;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset(Auth::user()->roles[0])){
            $rol = Auth::user()->roles[0];
        }else{
            $rol = '';
        }
        return  Inertia::render('Manager/Dashboard/Index', [
            'rol' => $rol,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function details() 
    {
        if(isset(Auth::user()->roles[0]->id)){
            $rol_id = Auth::user()->roles[0]->id;
        }else{
            $rol_id = '';
        }
        switch ($rol_id) {
            case 1: // Administrador
                return  [
                            'total_companie' => Companie::all()->count(),        
                            'total_user' => User::all()->count(), 
                            'competencias'  => Competencia::all(), 
                            'total_competencias' => Competencia::all()->count(),
                            'test' => Test::where('type_id',1)->with('test_detail')->get(),
                            'test_diagnosticos' => Test::where('type_id',2)->with('test_detail')->get(),
                        ];
                break;
            case 2: // Manager
                $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();
                $test = Test::whereIn('user_id', $companie->users->pluck('id')->toArray())->where('type_id',1)->with('test_detail')->get();
                $test_diagnosticos = Test::whereIn('user_id', $companie->users->pluck('id')->toArray())->where('type_id',2)->with('test_detail')->get();
                $date = Test::select('fecha')->where('user_id', Auth::user()->id)->latest()->first();
                
                return  [
                            'total_user' => $companie->users->count(),
                            'competencias'  => $companie->competencias,
                            'total_competencias' => $companie->competencias->count(),
                            'test' => $test,
                            'test_diagnosticos' => $test_diagnosticos,
                        ];
                break;
            case 3: // Empleado
                $test = Test::where('user_id', Auth::user()->id)->where('type_id',1)->with('test_detail')->get();
                $test_diagnosticos = Test::where('user_id', Auth::user()->id)->where('type_id',2)->with('test_detail')->get();
                $date = Test::select('fecha')->where('user_id', Auth::user()->id)->latest()->first();
                
                
                return  [
                            'test' => $test,
                            'test_diagnosticos' => $test_diagnosticos,
                            'date_last_test' =>  $date ? Carbon::parse($date->fecha)->format("d-m-Y") : '-',
                        ];
                break;
            default:
                return null;
                break;
        }
        $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();
        $test = Test::where('user_id', Auth::user()->id)->with('test_detail')->get();
        $date = Test::select('fecha')->where('user_id', Auth::user()->id)->latest()->first();
        
        return  [
                    'id'            => 1,
                    'competencias'  => $companie->competencias,
                    'total_competencias' => $companie->competencias->count(),
                    'test' => $test,
                    'date_last_test' =>  Carbon::parse($date->fecha)->format("d-m-Y"),
                ];
    }
}
