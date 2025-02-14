<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Test;
use App\Models\TestDetail;
use App\Models\TestStatus;
use App\Models\CompetenciaRelated;
use App\Models\Diagnostico;
use App\Models\DiagnosticoTest;
use App\Models\TestType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    /**
     * Crea una nueva Persona (Usuario no registrado en el sistema).
     * Se crea un Test relacionado a la persona.
     * 
     * @param  int  $Request
     *
     * @return \Illuminate\Http\JsonResponse 
     *
     */
    public function store(Request $request)
    {   
        try {
            DB::beginTransaction();
            $controllerPerson = New PersonController();
            if(Person::where('email',$request->email)->exists()){
                $data_person = $controllerPerson->update($request);
            }else{
                $data_person = $controllerPerson->store($request);
            }

            if($data_person['code'] == 200){
                $test = Test::create([
                    'person_id' => $data_person['data']['id'],
                    'fecha' => Carbon::now(),
                    'status_id' => TestStatus::select('id')->where('description', 'ABANDONED')->first()->id,
                ]);
                
                $form_competencias = explode(',', $request->input('form_competencias'));

                foreach ($form_competencias as $competencia) {
                    $id_competencia_related = CompetenciaRelated::select('id')
                                                ->where('competencia_id', $competencia)
                                                ->where('relate_id', $competencia)
                                                ->first();
                                                                
                    if ($id_competencia_related) {
                        $id = $id_competencia_related->id;
                    
                        TestDetail::create([
                            'test_id' => $test->id,
                            'competencia_related_id' =>  $id,
                            'score' => 0,
                        ]);
                    }
                }

                $controllerAfirmation = New AfirmationController();

                $afirmations = $controllerAfirmation->get_afirmations($form_competencias);
                DB::commit();
                return response()->json(['message'=>'Se registrado correctamente', 
                                         'data'   => $afirmations, 
                                         'person' => $data_person, 
                                         'test'   => $test], 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error'],500);
        }
    }

    /**
     * Almacena/Crea el Test relacionado a un usuario registrado en el sistema.
     * 
     * @param  int  $Request
     *
     * @return \Illuminate\Http\JsonResponse 
     *
     */
    public function storeUser(Request $request)
    {   
        try {
            DB::beginTransaction();
                $test = Test::create([
                    'user_id' => $request->id,
                    'fecha' => Carbon::now(),
                    'status_id' => TestStatus::select('id')->where('description', 'ABANDONED')->first()->id,
                ]);
                
                $form_competencias = explode(',', $request->form_competencias);
                foreach ($form_competencias as $competencia) {
                    $id_competencia_related = CompetenciaRelated::select('id')
                                                    ->where('competencia_id', $competencia)
                                                    ->where('relate_id', $competencia)
                                                    ->first();
                                                                
                    if ($id_competencia_related) {
                        $id = $id_competencia_related->id;
                    
                        TestDetail::create([
                            'test_id' => $test->id,
                            'competencia_related_id' =>  $id,
                            'score' => 0,
                        ]);
                    }
                }

                $controllerAfirmation = New AfirmationController();

                $afirmations = $controllerAfirmation->get_afirmations($form_competencias);
                DB::commit();
                return response()->json(['message'=>'Se registrado correctamente', 
                                         'data'   => $afirmations, 
                                         'person' => $request->id, 
                                         'test'   => $test], 200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error'],500);
        }
    }

    /**
     * Almacena/Crea el Test relacionado a un diagnostico un usuario registrado en el sistema.
     * Registra el diagnostico-test puede existir un Diagnostico 360
     * 
     * @param  int  $Request
     *
     * @return \Illuminate\Http\JsonResponse 
     *
     */
    public function storeUserDiagnostico(Request $request)
    {   
        try {
            DB::beginTransaction();
                if($request->user_id != "null"){
                    $user_id = $request->user_id;
                }else{
                    $user_id = Auth::user()->id;
                }
                $test = Test::create([
                    'user_id' => Auth::user()->id, // Usuario logueado en el sistema. 
                    'fecha' => Carbon::now(),
                    'status_id' => TestStatus::select('id')->where('description', 'ABANDONED')->first()->id,
                    'type_id' => TestType::select('id')->where('description', 'Diagnostico')->first()->id,
                    //'diagnostico_id' => $request->diagnostico_id
                ]);
                $diagnostico = Diagnostico::where('id', $request->diagnostico_id)->first();

                // Almaceno Diagnostico - Test
                DiagnosticoTest::create([
                    'user_id' => $user_id,
                    'diagnostico_id' => $diagnostico->id,
                    'test_id' => $test->id
                ]);

                if ($diagnostico) {
                    $form_competencias = $diagnostico->competencias->pluck('id')->toArray();
                } else {
                    $form_competencias = []; // En caso de que no se encuentre el diagnóstico
                }
                foreach ($form_competencias as $competencia) {
                    $id_competencia_related = CompetenciaRelated::select('id')
                                                    ->where('competencia_id', $competencia)
                                                    ->where('relate_id', $competencia)
                                                    ->first();
                                                                
                    if ($id_competencia_related) {
                        $id = $id_competencia_related->id;
                    
                        TestDetail::create([
                            'test_id' => $test->id,
                            'competencia_related_id' =>  $id,
                            'score' => 0,
                        ]);
                    }
                }

                $controllerAfirmation = New AfirmationController();

                $afirmations = $controllerAfirmation->get_afirmations($form_competencias);
                DB::commit();
                return response()->json(['message'=>'Se registrado correctamente', 
                                         'data'   => $afirmations, 
                                         'person' => $request->id, 
                                         'test'   => $test], 200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error'],500);
        }
    }

    public function usersByDiagnostico($id){
        $users =  User::whereIn('id', function($query) use($id){
            $query->select('users.id')
                  ->from('diagnosticos')
                  ->join('diagnostico_sector', 'diagnostico_sector.diagnostico_id', '=', 'diagnosticos.id')
                  ->join('sectores', 'sectores.id', '=', 'diagnostico_sector.sector_id')
                  ->join('users', 'users.sector_id', '=', 'sectores.id')
                  ->where('diagnosticos.id', $id);
        })->distinct()->get();

        return response()->json(['message'=>'Se han obtenido los usuarios', 'data' => $users],200);
    }

}
