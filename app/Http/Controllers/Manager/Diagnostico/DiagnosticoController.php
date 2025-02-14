<?php

namespace App\Http\Controllers\Manager\Diagnostico;

use App\Http\Controllers\Controller;
use App\Models\Diagnostico;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date_start' => 'required|date',
            'date_finish' => 'nullable|date|after_or_equal:date_start',
        ], [
            'name.required' => 'El Nombre es obligatorio.',
            'date_start.required' => 'La fecha de inicio es obligatoria.',
            'date_start.date' => 'La fecha de inicio debe ser una fecha válida.',
            'date_finish.nullable' => 'La fecha de fin puede ser nula.',
            'date_finish.date' => 'La fecha de fin debe ser una fecha válida.',
            'date_finish.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',
        ]);

        try {
            $diagnostico = Diagnostico::create($request->only(['name', 'date_start','date_finish','company_id','diagnostico_360', 'sector_id']));
            $diagnostico->competencias()->sync($request->competencias_id);
            $diagnostico->sectores()->sync($request->sectores_id);
            

            return response()->json(['message' => 'Diagnostico creado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al crear el Diagnostico', 'error' => $th->getMessage()], 500);
        }
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
        $request->validate([
            'name' => 'required',
            'date_start' => 'required|date',
            'date_finish' => 'nullable|date|after_or_equal:date_start',
        ], [
            'name.required' => 'El Nombre es obligatorio.',
            'date_start.required' => 'La fecha de inicio es obligatoria.',
            'date_start.date' => 'La fecha de inicio debe ser una fecha válida.',
            'date_finish.nullable' => 'La fecha de fin puede ser nula.',
            'date_finish.date' => 'La fecha de fin debe ser una fecha válida.',
            'date_finish.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',
        ]);

        try {
            $diagnostico = Diagnostico::findOrFail($id);
            
            $diagnostico->update($request->only(['name', 'date_start', 'date_finish', 'diagnostico_360', 'sector_id']));
            $diagnostico->competencias()->sync($request->competencias_id);
            $diagnostico->sectores()->sync($request->sectores_id);

            return response()->json(['message' => 'Diagnostico actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar el Diagnostico', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $diagnostico = Diagnostico::where('id', $id)->first();
            // TODO: Cual seria los parametros de control?
            $diagnostico->delete();
            return response()->json(['message' => 'Diagnostico eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al eliminar el Diagnostico', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function updateEstado(Request $request, $id)
    {
        try {
            $diagnostico = Diagnostico::findOrFail($id);
            $diagnostico->update($request->only(['status_id']));
            return response()->json(['message' => 'Diagnostico actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar el Diagnostico', 'error' => $th->getMessage()], 500);
        }
    }
}
