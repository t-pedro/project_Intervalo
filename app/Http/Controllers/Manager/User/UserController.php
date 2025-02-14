<?php

namespace App\Http\Controllers\Manager\User;

use App\Http\Controllers\Controller;
use App\Imports\UserCompanieImport;
use App\Models\Companie;
use App\Models\Rol;
use App\Models\Sector;
use App\Models\User;
use App\Notifications\NuevoUsuarioNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset(Auth::user()->roles[0]->id)){
            if(Auth::user()->roles[0]->id == 1){
                return  Inertia::render('Manager/User/List',
                [
                    'roles' => Rol::all(),
                    'empresas' => Companie::active()->get(),
                    'sectores' => Sector::active()->get()
                ]);
            }elseif(Auth::user()->roles[0]->id == 2){
                if(isset(Auth::user()->roles[0]->id)){
                    return  Inertia::render('Manager/User/List',
                    [
                        'roles' => Rol::where('id','<>',1)->get(),
                        'empresas' => Companie::where('id',Auth::user()->companies[0]->id)->active()->get(),
                        'sectores' => Sector::where('company_id',Auth::user()->companies[0]->id)->active()->get()
                    ]);
                }else{
                    abort(403, 'Acceso No autorizado | No posee empresa asignada');
                }
            }else{
                abort(403, 'Acceso No autorizado');
            }
        }else{
            abort(403, 'Acceso No autorizado | No posee roles validos');
        }
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
    public function store(Request $request){

        DB::beginTransaction();
        try {
            $randomPassword = Str::random(15); 

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($randomPassword) 
            ]);

            // Asignar Empresa
            $user->companies()->sync($request->empresa_id);

            // Asignar Rol
            $user->roles()->sync($request->rol_id);
            
            $user->notify(new NuevoUsuarioNotification($randomPassword));
            
            DB::commit();
            return response()->json(['message'=>'Se ha almacenado correctamente el usuario'], 200);    
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error al momento de almacenar el usuario'], 500);
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
        DB::beginTransaction();
        try {
            $randomPassword = Str::random(15); 

            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'sector_id' => $request->sector_id,
                'password' => bcrypt($randomPassword) 
            ]);
            
            $user = User::find($id);

            // Asignar Empresa
            $user->companies()->sync($request->empresa_id);

            // Asignar Rol
            $user->roles()->sync($request->rol_id);
            
            //$user->sendEmailVerificationNotification();
            DB::commit();
            return response()->json(['message'=>'Se ha actualizado correctamente el usuario'], 200);    
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error al momento de almacenar el usuario'], 500);
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
        //
    }

    public function list(){

        $length = request('length');
        
        $result = User::query();

        if(request('name')){
            $name = request('name');
            $result->where('name', 'LIKE', '%'.$name.'%');
        }

        if(request('email')){
            $email = request('email');
            $result->where('email', 'LIKE', '%'.$email.'%');
        }

        if(Auth::user()->roles[0]->id == 2){
            $idCompany = Auth::user()->companies[0]->id;
            $result->whereIn('id', function ($sub) use($idCompany) {
                $sub->selectRaw('users.id')
                    ->from('users')
                    ->join('companies_users', 'users.id', '=', 'companies_users.user_id')
                    ->where('companies_users.companie_id', $idCompany);
            });
            /* $result->join('companies_users as cu','users.id', '=', 'cu.user_id')
                    ->where('cu.companie_id',  $idRol); */
        }

        return  $result->orderBy("users.created_at", 'DESC')
                        ->paginate($length)
                        ->withQueryString()
                        ->through(fn ($u)   => [
                            'id'        => $u->id,
                            'name'      => $u->name,
                            'email'     => $u->email,
                            'empresa'   => $u->companies->first(),
                            'rol'       => $u->roles->first(),
                            'sector'    => $u->sector    
                        ]);
    } 

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
    
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if($status === 'passwords.sent'){
                return response()->json(['message' => 'Se ha enviado correctamente el email de recupero.'], 200);
            }else{
                return response()->json(['message'=>'Demasiados intentos de restablecimiento de contraseña. Por favor, inténtelo de nuevo más tarde.'], 203);    
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error al momento de enviar el email'], 500);
        }

    }

    public function importView()
    {
        if(isset(Auth::user()->roles[0]->id)){
            if(Auth::user()->roles[0]->id == 1){
                return  Inertia::render('Manager/User/Import',
                [
                    'companies' => Companie::active()->get()
                ]);
            }elseif(Auth::user()->roles[0]->id == 2){
                if(isset(Auth::user()->roles[0]->id)){
                    return  Inertia::render('Manager/User/Import',
                    [
                        'companies' => Companie::where('id',Auth::user()->companies[0]->id)->active()->get()
                    ]);
                }else{
                    abort(403, 'Acceso No autorizado | No posee empresa asignada');
                }
            }else{
                abort(403, 'Acceso No autorizado');
            }
        }else{
            abort(403, 'Acceso No autorizado | No posee roles validos');
        }
        
    }

    public function import(Request $request)
    {

        if( $request->file('file')){
                $archivoCSV = $request->file('file');
                try {
                    $import = new UserCompanieImport($request->idCompanie);
                    Excel::import($import, $archivoCSV);
                    $status = $import->getStatus();
                    return response()->json(['message' => 'Se ha finalizado el proceso de importacion de Entidades.', 'status' => $status], 200);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Error al procesar el archivo.'], 203);
                }
        }else{
            return response()->json(['message' => 'Error al procesar el importador. Contacte al Administrador'], 203);
        }
    }

    public function donwloadTemplate(){
        $url = public_path('templates/templateImportUserMasivo.xlsx');
        return Response::download($url);
    }

}
