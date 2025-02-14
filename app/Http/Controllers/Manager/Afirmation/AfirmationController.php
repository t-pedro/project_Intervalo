<?php

namespace App\Http\Controllers\Manager\Afirmation;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Afirmation;
use App\Models\Competencia;

class AfirmationController extends Controller
{
    
    public function index()
    {
        return  Inertia::render('Manager/Afirmations/List');
    }
    
    public function list()
    {
        $length = request('length');
        $result = Afirmation::query();
        
        if(request('search')){
            $result->where('text','LIKE', '%' . request('search') . '%' );
        }

        return $result->paginate($length)
                        ->withQueryString()
                        ->through(fn ($a) => [
                                    'id'     => $a->id,
                                    'text'   => $a->text
                        ]);
    }

    public function create()
    {
        
        $competencia = Competencia::all()->toArray();
        
        $comp_list = array_map(function ($c){
                        return 
                        [
                            'value'=> $c['id'],
                            'label' => $c['competencia']
                        ];
                    }, $competencia);


        return  Inertia::render('Manager/Afirmations/Create',[
            'competencias' => $comp_list
        ]);        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        
        Afirmation::create($request->all())->competencias()->attach($request->input("tags"));

        return Redirect::route('afirmation');
        
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Afirmation $afirmation)
    {

        $competencia = Competencia::all()->toArray();
        
        $comp_list = array_map(function ($c){
                        return 
                        [
                            'value'=> $c['id'],
                            'label' => $c['competencia']
                        ];
                    }, $competencia);

        $competencias_asignadas = $afirmation->Competencias;

        return Inertia::render('Manager/Afirmations/Edit', [
            'afirmation'      => $afirmation,
            // 'afirmation_compe' =>$competencias_asignadas,
            'competencias'    => $comp_list
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Afirmation $afirmation)
    {
        $afirmation->update($request->all());
        $afirmation->Competencias()->sync($request->input('tags'));

        return Redirect::route('afirmation');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Afirmation $afirmation)
    {
        $afirmation->delete();

        return Redirect::back()->with('success', 'Afirmation Eliminado');
    }

}
