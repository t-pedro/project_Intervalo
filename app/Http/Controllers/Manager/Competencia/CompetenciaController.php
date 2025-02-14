<?php

namespace App\Http\Controllers\Manager\Competencia;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Competencia;
use App\Models\CompetenciaRelated;
use App\Models\Category;
use App\Models\Capsule;
use App\Imports\CompetenciaImport;
use App\Imports\CompetenciaRelatedImport;
use Illuminate\Support\Facades\Session;

class CompetenciaController extends Controller
{
    public function index()
    {
        return  Inertia::render('Manager/Competencias/List');

    }

    public function list()
    {
    $length = request('length');
    
    $result = Competencia::query()->with('afirmations')->with('competencias_relate')->with(['competencias_relate.competencia_relate']);
    
    if (request('search')) {
        $result->where('competencia', 'LIKE', '%' . request('search') . '%');
    }

    return $result->paginate($length)
        ->withQueryString()
        ->through(function ($c) {
            $relatedCompetencias = $c->competencias_relate->unique('competencia_relate.competencia')->toArray();
            $relatedData = [];

            foreach ($relatedCompetencias as $relatedCompetencia) {
                $relatedData[] = [
                    'competencia' => $relatedCompetencia['competencia_relate']['competencia'] ?? 0,
                    'feedback_approve' => $relatedCompetencia['feedback_approve'] ? true : false ,
                    'feedback_disapprove' => $relatedCompetencia['feedback_disapprove'] ? true : false 
                ];
            }

            return [
                'id' => $c->id,
                'competencia' => $c->competencia,
                'afirmations' => $c->afirmations->count(),
                'related' => $relatedData,
                'has_self_relation' => $c->has_self_relation
            ];
        });
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $capsulas = Capsule::all()->toArray();
        
        $capsulas_list = array_map(function ($c){
                        return 
                        [
                            'value'=> $c['id'],
                            'label' => $c['title']
                        ];
                    }, $capsulas);
        
        return  Inertia::render('Manager/Competencias/Create',
                                    [ "categories"  => Category::all(),
                                      "capsules"    => $capsulas_list]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        Competencia::create($request->all())->capsules()->attach($request->input("tags"));
        $competencia = Competencia::latest()->first();
        CompetenciaRelated::create([
            'competencia_id' => $competencia->id,
            'relate_id' => $competencia->id,
            'feedback_approve' => $request->feedback_approve,
            'feedback_disapprove' => $request->feedback_disapprove
        ]);
        return Redirect::route('competencia');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Competencia $competencia)
    {

        $capsulas = Capsule::all()->toArray();
        
        $capsulas_list = array_map(function ($c){
                        return 
                        [
                            'value'=> $c['id'],
                            'label' => $c['title']
                        ];
                    }, $capsulas);

        $capsulas_asignadas = $competencia->Capsules;
        $afirmations_asignadas = $competencia->Afirmations;
                    //dd($competencia->competencias_relate[0]->competencia);
        $competencia_relate = array();

        foreach ($competencia->competencias_relate as $value) {
            $c = array (
                'competencia' => $value->competencia->competencia,
                'competencia_id' => $value->competencia_id,
                'competencia_relate' => $value->competencia_relate->competencia,
                'relate_id' => $value->relate_id,
                'feedback_approve' => $value->feedback_approve,
                'feedback_disapprove' => $value->feedback_disapprove,
                'action' => 'U'
            );
            array_push($competencia_relate, $c);
        }
        return Inertia::render('Manager/Competencias/Edit', [
                'competencia' => $competencia,
                "categories"  => Category::all(),
                "capsules"    => $capsulas_list,
                "competencias" => Competencia::all(),
                "competencias_related" => $competencia_relate
            ]);

        }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competencia $competencia)
    {
        foreach ($request->input('competencias_related') as $competencia_related) {
            switch ($competencia_related['action']) {
                case 'D':
                    //DELETED
                    CompetenciaRelated::where('competencia_id', $competencia_related['competencia_id'])->where('relate_id', $competencia_related['relate_id'])->delete();
                    break;
                case 'N':
                    //NUEVA COMPETENCIA
                    CompetenciaRelated::create([
                        'competencia_id' => $competencia_related['competencia_id'],
                        'relate_id' => $competencia_related['relate_id'],
                        'feedback_approve' => $competencia_related['feedback_approve'],
                        'feedback_disapprove' => $competencia_related['feedback_disapprove']
                    ]);
                    break;
                case 'U':
                    //UPDATE COMPETENCIA
                    CompetenciaRelated::where('competencia_id', $competencia_related['competencia_id'])->where('relate_id', $competencia_related['relate_id'])->update([
                        'feedback_approve' => $competencia_related['feedback_approve'],
                        'feedback_disapprove' => $competencia_related['feedback_disapprove']
                    ]);
                    break;
                default:
                    # code...
                    break;
            }
        }
        $competencia->update($request->all());
        $competencia->Capsules()->sync($request->input('tags'));


        return Redirect::route('competencia');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competencia $competencia)
    {
        $competencia->delete();

        return Redirect::back()->with('success', 'Competencia Eliminado');
    }


    /*** IMPORT ***/
    public function import()
    {
        return  Inertia::render('Manager/Competencias/Import',[
            'toast' => Session::get('toast')
        ]);
        
    }    
    
    public function importfile(Request $request )
    {

        if( $request->file('import_file')){
            try {
                $path = $request->file('import_file');
                $import = new CompetenciaImport();
                $data = Excel::import($import, $path);
                $status = $import->getStatus();
                return Redirect::back()->with(['toast' => ['message' => $status, 'status' => '200']]);
            } catch (\Throwable $th) {
                return Redirect::back()->with(['toast' => ['message' => 'Error al momento de procesar el archivo', 'status' => '203']]);
            }
        }else{
            return Redirect::back()->with(['toast' => ['message' => 'Debe cargar un archivo', 'status' => '203']]);
        }
        
    } 

    public function importfilerelated(Request $request )
    {

        if( $request->file('import_file')){
            try {
                $path = $request->file('import_file');
                $import = new CompetenciaRelatedImport();
                $data = Excel::import($import, $path);
                $status = $import->getStatus();
                return Redirect::back()->with(['toast' => ['message' => $status, 'status' => '200']]);
            } catch (\Throwable $th) {
                dd($th);
                return Redirect::back()->with(['toast' => ['message' => 'Error al momento de procesar el archivo', 'status' => '203']]);
            }
        }else{
            return Redirect::back()->with(['toast' => ['message' => 'Debe cargar un archivo', 'status' => '203']]);
        }
        
    }

    public function autorelacionar()
    {
        try {
            $competencias = Competencia::all();
            foreach ($competencias as $competencia) {
                if (!CompetenciaRelated::where('competencia_id', $competencia->id)->where('relate_id', $competencia->id)->exists()) {
                    CompetenciaRelated::create(
                        [
                            'competencia_id' => $competencia->id,
                            'relate_id' => $competencia->id,
                            'feedback_approve' => '',
                            'feedback_disapprove' => ''
                        ] 
                    );
                }
            }
            return response()->json(['message' => 'Competencias autorelacionadas correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al momento de autorelacionar las competencias', 'error' => $th->getMessage()], 500);
        }
    }

}
