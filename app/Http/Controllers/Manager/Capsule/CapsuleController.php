<?php

namespace App\Http\Controllers\Manager\Capsule;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

use App\Models\Capsule;

class CapsuleController extends Controller
{
    public function index()
    {
        return  Inertia::render('Manager/Capsules/List');
        /* $result = Capsule::query();
        return  Inertia::render('Manager/Capsules/List', 
        [
            
            'capsules' =>  $result->paginate(999)
                                    ->withQueryString()
                                    ->through(fn ($c) => [
                                        'id'          => $c->id,
                                        'title'       => $c->title,
                                        'url'       => $c->url,
                                        'description' => STR::limit($c->description, 50, '...'),
                                        'image'       => $c->image,
                                        ])
        ]); */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return  Inertia::render('Manager/Capsules/Create');       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            if (is_file($request->image)) {
                $nombre = $request->title.'-'.Str::random(5).'.'.$request->image->getClientOriginalExtension();
                $path = Storage::disk('capsulas')->put($nombre,file_get_contents($request->image->getPathName()));
                if($path){
                    Capsule::create([
                        'title' => $request->title,
                        'url' => $request->url,
                        'description' => $request->description,
                        'image' => $nombre,
                    ]);
                }else{
                    return response()->json(['message'=>'No se ha podido almacenar la imagen','title'=>'Capsula'], 203);
                }              
            }else{
                Capsule::create([
                    'title' => $request->title,
                    'url' => $request->url,
                    'description' => $request->description,
                ]);
            }
            
            return response()->json(['message'=>'Capsula creada con éxito','title'=>'Capsula'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error','title'=>'Capsula'], 203);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
           $capsule = Capsule::find($request->id);
            if($capsule->image){
                Storage::disk('capsulas')->delete($capsule->image);
            }
            if (is_file($request->image)) {
                $nombre = $request->title.'-'.Str::random(5).'.'.$request->image->getClientOriginalExtension();
                $path = Storage::disk('capsulas')->put($nombre,file_get_contents($request->image->getPathName()));
                if($path){
                    Capsule::where('id', $request->id)->update([
                        'title' => $request->title,
                        'url' => $request->url,
                        'description' => $request->description,
                        'image' => $nombre,
                    ]);
                }else{
                    return response()->json(['message'=>'No se ha podido almacenar la imagen','title'=>'Capsula'], 203);
                }              
            }else{
                Capsule::where('id', $request->id)->update([
                    'title' => $request->title,
                    'url' => $request->url,
                    'description' => $request->description,
                ]);
            }
            return response()->json(['message'=>'Capsula actualizada con éxito','title'=>'Capsula'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error','title'=>'Capsula'], 203);
        }
    }

    public function destroy($id)
    {
        try {
            $capsule = Capsule::find($id);

            if($capsule->competencias->isEmpty()){         
                
                Storage::disk('capsulas')->delete($capsule->image);
                $capsule->delete();
                return response()->json(['message'=>'Capsula eliminada con éxito','title'=>'Capsula'], 200);
            }else{
                return response()->json(['message'=>'Capsula posee competencias relacionadas','title'=>'Capsula'], 203);
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error al momento de eliminar','title'=>'Capsula'], 500);
        }
        
    }

    public function list(){

        return  Capsule::orderBy("created_at", 'DESC')
                        ->paginate(999)
                        ->withQueryString()
                        ->through(fn ($c) => [
                            'id'          => $c->id,
                            'title'       => $c->title,
                            'url'         => $c->url,
                            'description' => STR::limit($c->description, 50, '...'),
                            'image'       => Storage::disk('capsulas')->url($c->image),
                        ]);

    }  
    
    



}
