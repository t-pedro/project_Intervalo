<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function store($request)
    {   
        try {
            $person = Person::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email
            ]);

            return $data = [
                'code' => 200,
                'data' => $person,
                'message' => 'Se ha creado correctamente la persona'
            ];
        } catch (\Throwable $th) {
            return $data = [
                'code' => 500,
                'data' => '',
                'message' => 'Se ha producido un error'
            ];
        }
    }

    public function update($request) 
    {   
        try {
            Person::where('email',$request->email)->update([
                'name' => $request->name,
                'lastname' => $request->lastname
            ]);
            //dd($person);
            return $data = [
                'code' => 200,
                'data' => Person::where('email',$request->email)->first(),
                'message' => 'Se ha creado correctamente la persona'
            ];
        } catch (\Throwable $th) {
            dd($th);
            return $data = [
                'code' => 500,
                'data' => '',
                'message' => 'Se ha producido un error'
            ];
        }
    }
}
