<?php

namespace App\Http\Controllers\Manager\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session as FacadesSession;
use PhpParser\Node\Stmt\TryCatch;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Manager/Category/List', [
            'toast' => FacadesSession::get('toast')
        ]);
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
            'title' => 'required|unique:categories',
            'description' => 'string|max:255'
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.unique' => 'El título ya ha sido utilizado.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        try {
            Category::create($request->all());
            return response()->json(['message' => 'Categoria creada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al crear la Categoria', 'error' => $th->getMessage()], 500);
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
            'title' => 'required|unique:categories,title,' . $id, // Excluye el ID actual
            'description' => 'nullable|string|max:255', // La descripción puede ser nula
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.unique' => 'El título ya ha sido utilizado.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        try {
            $category = Category::findOrFail($id);
            
            $category->update($request->only(['title', 'description']));
            return response()->json(['message' => 'Categoria actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar la Categoria', 'error' => $th->getMessage()], 500);
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
            $category = Category::where('id', $id)->first();
            if($category->competencias()->count() > 0 ){
                return response()->json(['message' => 'No se puede eliminar la categoría porque tiene competencias asociadas.'], 403);
            }

            $category->delete();
            return response()->json(['message' => 'Categoria eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al eliminar la Categoria', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Returns a filtered list of categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function list()
    {
        $length = request('length');

        $result = Category::query();

        if (request('description')) {
            $description = request('description');
            $result->where('description', 'LIKE', '%' . $description . '%');
        }

        if (request('title')) {
            $title = request('title');
            $result->where('title', 'LIKE', '%' . $title . '%');
        }

        return  $result->with('competencias')
        ->orderBy("title", 'ASC')
        ->paginate($length)
        ->withQueryString();
    }

    public function active($id){
        try {
            $category = Category::where('id', $id)->first();
            if($category->competencias()->count() > 0 ){
                return response()->json(['message' => 'No se puede eliminar la categoría porque tiene competencias asociadas.'], 403);
            }

            $category->active = !$category->active;
            $category->save();
            return response()->json(['message' => 'Categoria actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar la Categoria', 'error' => $th->getMessage()], 500);
        }
    }
}
