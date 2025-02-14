<?php

namespace App\Http\Controllers\Manager\Sectores;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'name' => 'required'
        ], [
            'name.required' => 'El Nombre es obligatorio.',
        ]);

        try {
            Sector::create($request->only(['name','company_id']));
            return response()->json(['message' => 'Sector creado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al crear el Sector', 'error' => $th->getMessage()], 500);
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
            'name' => 'required'
        ], [
            'name.required' => 'El Nombre es obligatorio.'
        ]);

        try {
            $sector = Sector::findOrFail($id);
            
            $sector->update($request->only(['name']));
            return response()->json(['message' => 'Sector actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar el Sector', 'error' => $th->getMessage()], 500);
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
            $sector = Sector::where('id', $id)->first();
            /* if($category->competencias()->count() > 0 ){
                return response()->json(['message' => 'No se puede eliminar la categoría porque tiene competencias asociadas.'], 403);
            } */

            $sector->delete();
            return response()->json(['message' => 'Sector eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al eliminar el Sector', 'error' => $th->getMessage()], 500);
        }
    }

    public function active($id){
        try {
            $sector = Sector::where('id', $id)->first();
            /* if($sector->competencias()->count() > 0 ){
                return response()->json(['message' => 'No se puede eliminar la categoría porque tiene competencias asociadas.'], 403);
            } */
            // TODO: Definir controles.

            $sector->active = !$sector->active;
            $sector->save();
            return response()->json(['message' => 'Sector actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar el Sector', 'error' => $th->getMessage()], 500);
        }
    }
}
