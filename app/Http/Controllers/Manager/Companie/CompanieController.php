<?php

namespace App\Http\Controllers\Manager\Companie;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Companie;
use App\Models\Competencia;
use App\Models\Diagnostico;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CompanieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Manager/Companie/List', [
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
        return  Inertia::render(
            'Manager/Companie/Create',
            [
                "competencias"  => Competencia::all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $companie = Companie::create([
                'description' => $request->description,
                'address' => $request->address ?? null,
                'contact_name' => $request->contact_name ?? null,
                'contact_email' => $request->contact_email ?? null,
                'contact_phone' => $request->contact_phone ?? null,
            ]);

            foreach ($request->competencias_select as $c) {
                $companie->competencias()->attach($c['id']);
            }
            return Redirect::route('companie')->with('toast', ['message' => 'Empresa creada con éxito', 'status' => 200]);
        } catch (\Throwable $th) {
            return Redirect::route('companie')->with('toast', ['message' => 'Se ha producido un error al momento de crear la Empresa', 'status' => 203]);
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
        $companie = Companie::where('id', $id)->first();

        return Inertia::render('Manager/Companie/Edit', [
            'competencias'  => Competencia::all(),
            'companie'      => $companie,
            'competencias_asociadas' => $companie->competencias,
            //'sectores' => Sector::where('company_id', $companie->id)->get()
        ]);
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
        try {
            Companie::where('id', $request->id)->update([
                'description' => $request->description,
                'address' => $request->address ?? null,
                'contact_name' => $request->contact_name ?? null,
                'contact_email' => $request->contact_email ?? null,
                'contact_phone' => $request->contact_phone ?? null,
                'show_competencias' => $request->show_competencias ?? false,
            ]);

            $companie = Companie::find($request->id);
            $competenciasParaSync = [];
            foreach ($request->competencias_select as $c) {
                $competenciasParaSync[] = $c['id']; //$companie->competencias()->attach($c['id']);
            }

            $companie->competencias()->sync($competenciasParaSync);
            return Redirect::route('companie')->with('toast', ['message' => 'Empresa actualizada con éxito', 'status' => 200]);
        } catch (\Throwable $th) {
            return Redirect::route('companie')->with('toast', ['message' => 'Se ha producido un error al momento de actualizar la Empresa', 'status' => 203]);
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
            $companie = Companie::where('id', $id)->first();

            $companie->delete();
            return response()->json(['message' => 'Empresa eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al eliminar la Empresa', 'error' => $th->getMessage()], 500);
        }
    }

    public function active($id){
        try {
            $company = Companie::where('id', $id)->first();
            /* if($category->competencias()->count() > 0 ){
                return response()->json(['message' => 'No se puede eliminar la categoría porque tiene competencias asociadas.'], 403);
            } */

            $company->active = !$company->active;
            $company->save();
            return response()->json(['message' => 'Empresa actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar la Empresa', 'error' => $th->getMessage()], 500);
        }
    }

    public function list()
    {
        $length = request('length');

        $result = Companie::query();

        if (request('description')) {
            $description = request('description');
            $result->where('description', 'LIKE', '%' . $description . '%');
        }

        if (request('contact')) {
            $contact = request('contact');
            $result->Where('contact_name', 'LIKE', '%' . $contact . '%')
                ->orWhere('contact_email', 'LIKE', '%' . $contact . '%')
                ->orWhere('contact_phone', 'LIKE', '%' . $contact . '%');
        }


        return  $result->orderBy("created_at", 'DESC')
            ->paginate($length)
            ->withQueryString()
            ->through(fn($c)   => [
                'id'            => $c->id,
                'description'   => $c->description,
                'address'       => STR::limit($c->address, 50, '...'),
                'contact_name'  => $c->contact_name,
                'contact_email' => $c->contact_email,
                'contact_phone' => $c->contact_phone,
                'active'        => $c->active,
                'competencias'  => $c->competencias,
                'diagnosticos'  => $c->diagnosticos,
            ]);
    }

    public function listUserByCompanie($id)
    {
        $length = request('length');

        $result = User::query();

        $result->whereIn('id', function ($sub) use ($id) {
            $sub->selectRaw('users.id')
                ->from('users')
                ->join('companies_users', 'users.id', '=', 'companies_users.user_id')
                ->where('companies_users.companie_id', $id);
        });

        return  $result->orderBy("created_at", 'DESC')
            ->paginate($length)
            ->withQueryString()
            ->through(fn($c) => [
                'id'    => $c->id,
                'name'  => $c->name,
                'email' => $c->email,
                'rol'   => $c->roles[0]->name
            ]);
    }


    /* MY COMPANIE*/

    public function myCompanie()
    {
        $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();

        return  Inertia::render('Manager/Companie/MyCompanie/Index', [
            'toast' => FacadesSession::get('toast'),
            'companie' => $companie,
            'competencias_asociadas' => $companie->competencias,
            'categorias' =>  Category::with('competencias')->get(),
        ]);
    }

    // Manejo de Diagnosticos
    public function CompanyDiagnosticosById($id)
    {
        $length = request('length') ?? 5;

        // Obtener los diagnósticos relacionados con la compañía
        $result = Diagnostico::with('status', 'competencias', 'sectores') // Cargar relaciones aquí
            ->where('company_id', $id)
            ->orderBy('date_start', 'DESC') // Ordenar por fecha de inicio
            ->paginate($length) // Paginación
            ->withQueryString(); // Mantener los parámetros de la consulta

            // Asegúrate de que cada diagnóstico tenga el getter en la colección
        $result->getCollection()->transform(function ($diagnostico) {
            $diagnostico->setAttribute('users_for_diagnostico', $diagnostico->users_for_diagnostico);
            $diagnostico->setAttribute('users_have_test', $diagnostico->users_have_test);
            return $diagnostico;
        });

        return $result; // Devolver el resultado
    }

    // Manejo de Diagnosticos
    public function CompanySectoresById($id)
    {
        $length = request('length') ?? 5;

        $result = Sector::query();

        $result->where('company_id', $id);

        return  $result->with('users')
            ->orderBy("name", 'ASC')
            ->paginate($length)
            ->withQueryString();
    }
}
