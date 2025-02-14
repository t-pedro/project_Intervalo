<?php
namespace App\Http\Controllers\Manager\Afirmation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

use App\Models\Afirmation;
use App\Imports\AfirmationImport;


class ImportController extends Controller
{
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function import()
    {
        return  Inertia::render('Manager/Afirmations/Import',[
            'toast' => Session::get('toast')
        ]);

    }    

    public function importfile(Request $request )
    {

        if( $request->file('import_file')){
            try {
                $path = $request->file('import_file');
                $import = new AfirmationImport();
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

    // public function listPersons(){

    //     $result = Person::with('address')
    //                     ->with('tagskeysvalueses');

    //     $length = request('length');


    //     if(request('import_id')){
    //         $result->where('import_id', request('import_id'));
    //     }

    //     if(request('date')){

    //         $date = json_decode(request('date'));
            
    //         $from = date($date[0]);
    //         $to = date('Y-m-d', strtotime("+1 day", strtotime($date[1])));
                       

    //         $result->whereBetween('created_at', [$from, $to]);

    //     }

    //     if(request('tags')){
    //         $key_tags = json_decode(request('tags'));                
    //         $tags = Tagskeysvalues::select('id')->whereIn('key', $key_tags )->get();

    //         foreach($tags as $tag ){
    //             $result->wherehas('tagskeysvalueses', 
    //                                function($query) use ($tag){
    //                                     $query = $query->where('tagskeysvalues_id', $tag['id']);
    //                                });
    //         }
    //     }

    //     if(request('origen')){
    //         $result->where('source', request('origen'));
    //     }

    //     if(request('company')){
    //         $result->where('company', request('company'));
    //     }

    //     if(request('name')){
    //         $result->where('name', 'LIKE', '%' . request('name') .'%')
    //                ->orWhere('lastname', 'LIKE', '%' . request('name') .'%');
    //     }

    //     if(request('email')){
    //         $result->where('email', 'LIKE', '%' . request('email') . '%' );
    //     }


    //     return  $result->orderBy("created_at", 'DESC')
    //                     ->paginate($length)
    //                     ->withQueryString()
    //                     ->through(fn ($person) => [
    //                         'id' => $person->id,
    //                         'import_id' => $person->import_id,
    //                         'import_status' => $person->import_status,
    //                         'name' => $person->name,
    //                         'lastname' => $person->lastname,
    //                         'dni' => $person->dni,
    //                         'email' => $person->email,
    //                         'phone' => $person->phone,
    //                         'cellphone' => $person->cellphone,
    //                         'birthdate' => $person->birthdate,
    //                         'gender' => $person->gender,
    //                         'company' => $person->company,
    //                         'notes' => $person->notes,
    //                         'source' => $person->source,
    //                         'mail_duplicated' => Person::active()->where('email',$person->email)->exists()
                            
                            
    //                         ]); 
   
    // }

    // public function activePersons(Request $request){
        
    //     switch($request->type){
    //         case 'person': 
    //             Person::whereId($request->id)
    //                     ->update(['import_status' => null]);                
                
    //             break;
    //         case 'import':
    //             Person::where('import_id',$request->id )
    //                     ->update(['import_status' => null]);
    //             break;    
    //     }
               
    //     return response()->json(array('message' => 'Activado con éxito'), 200);

    // }
}
