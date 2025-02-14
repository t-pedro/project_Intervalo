<?php

namespace App\Http\Controllers\Manager\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

use App\Models\Test;
use App\Models\TestDetail;
use App\Models\Competencia;

use App\Exports\TestsExport;
use App\Models\Companie;
use App\Models\Diagnostico;
use App\Models\Sector;
use App\Models\TestStatus;
use App\Models\TestType;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index()
    {
        $rol_id = Auth::user()->roles[0]->id;
        $companies = null;
        $sectores = null;
        $competencias = null;
        switch ($rol_id) {
            case 1: // Administrador // Obtengo todas las companias, sectores y competencias. 
                $companies = Companie::all();
                $sectores = Sector::all();
                $competencias = Competencia::all();
                $diagnosticos = Diagnostico::all();
                break;
            case 2: // Manager // debo obtener sus sectores y sus competencias.
                $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();
                $sectores = Sector::where('company_id', Auth::user()->companies[0]->id)->get();
                $competencias = $companie->competencias;
                $diagnosticos = Diagnostico::where('company_id', Auth::user()->companies[0]->id)->get();
                break;
            case 3: // Empleado
                $companie = Companie::where('id', Auth::user()->companies[0]->id)->first();
                $competencias = $companie->competencias;
                $diagnosticos = Diagnostico::where('company_id', Auth::user()->companies[0]->id)->get();
                break;
            default:
                return null;
                break;
        }
        return Inertia::render(
            'Manager/Test/List',
            [
                'competencias' => $competencias,
                'diagnosticos' => $diagnosticos,
                'companies' => $companies,
                'sectores' => $sectores,
                'status' => TestStatus::all(),
                'test_types' => TestType::all(),
            ]
        );
    }

    public function listTest()
    {

        $length = request('length');
        $sort_by = request('sort_by') ?? 'test.fecha';
        $sort_order = request('sort_order') ?? 'DESC';
        $result = Test::query();

        if (request('search')) {
            // Asegúrate de que las relaciones 'persons' y 'users' están correctamente definidas en el modelo Test
            $result->whereHas('person', function($query) {
                $query->where('name', 'LIKE', '%' . request('search') . '%')
                      ->orWhere('lastname', 'LIKE', '%' . request('search') . '%');
            });
            
            $result->orWhereHas('user', function($query) {
                $query->where('name', 'LIKE', '%' . request('search') . '%');
            });
        }

        if (request('competencia_id')) {
            $result->whereHas('test_detail.competencia_related', function($query) {
                $query->where('competencia_id', request('competencia_id'));
            });
        }

        if (request('diagnostico_id')) {
            $result->whereHas('diagnostico', function($query) {
                $query->where('diagnostico_id', request('diagnostico_id'));
            });
        }

        if (request('sector_id')) {
            $result->whereHas('user', function($query) {
                $query->where('sector_id', request('sector_id'));
            });
        }

        if (request('test_type_id')) {
            $result->whereHas('type', function($query) {
                $query->where('id', request('test_type_id'));
            });
        }

        if (request('status_id')) {
            $result->whereHas('status', function($query) {
                $query->where('id', request('status_id'));
            });
        }

        if (request('company_id')) {
            $result->whereHas('user.companies', function($query) {
                $query->where('companie_id', request('company_id'));
            });
        }

        return $result->orderBy($sort_by, $sort_order)
                      ->paginate($length)
                      ->withQueryString();

    }

    // public function list()
    // {

    //     $length = request('length');
    //     $sort_by = request('sort_by') ?? 'test.fecha';
    //     $sort_order = request('sort_order') ?? 'DESC';

    //     $result = TestDetail::query();
    //     $result->select('test.*', 'test_detail.*', 'diagnosticos.*');
    //     $result->leftjoin('test', 'test_detail.test_id', '=', 'test.id');
    //     $result->leftjoin('persons', 'persons.id', '=', 'test.person_id');
    //     $result->leftjoin('users', 'users.id', '=', 'test.user_id');
    //     $result->leftjoin('competencias_related', 'test_detail.competencia_related_id', '=', 'competencias_related.id');
    //     $result->leftjoin('competencias', 'competencias.id', '=', 'competencias_related.competencia_id');
    //     $result->leftjoin('test_types', 'test_types.id', '=', 'test.type_id');
    //     $result->leftjoin('test_status', 'test_status.id', '=', 'test.status_id');
    //     $result->leftjoin('diagnostico_test', 'diagnostico_test.test_id', '=', 'test.id');
    //     $result->leftjoin('diagnosticos', 'diagnosticos.id', '=', 'diagnostico_test.diagnostico_id');


    //     if (request('search')) {
    //         $result->where('persons.name', 'LIKE', '%' . request('search') . '%');
    //         $result->orWhere('persons.lastname', 'LIKE', '%' . request('search') . '%');
    //         $result->orWhere('users.name', 'LIKE', '%' . request('search') . '%');
    //     }

    //     if (request('competencia_id')) {
    //         $result->where('competencias_related.competencia_id', request('competencia_id'));
    //     }

    //     if (request('diagnostico_id')) {
    //         $result->where('diagnosticos.id', request('diagnostico_id'));
    //     }

    //     if (request('sector_id')) {
    //         $result->where('users.sector_id', request('sector_id'));
    //     }

    //     if (request('test_type_id')) {
    //         $result->where('test.type_id', request('test_type_id'));
    //     }

    //     if (request('status_id')) {
    //         $result->where('test.status_id', request('status_id'));
    //     }

    //     if (Auth::user()->roles[0]->id == 2) {
    //         $idCompany = Auth::user()->companies[0]->id;

    //         $result->whereIn('test.user_id', function ($sub) use ($idCompany) {
    //             $sub->selectRaw('users.id')
    //                 ->from('users')
    //                 ->join('companies_users', 'users.id', '=', 'companies_users.user_id')
    //                 ->where('companies_users.companie_id', $idCompany);
    //         });
    //     }


    //     return $result->with(
    //         'competencia_related',
    //         'competencia_related.competencia',
    //         'test',
    //         'test.user',
    //         'test.person',
    //         'test.diagnostico.diagnostico',
    //         'test.status',
    //         'test.type'
    //     )
    //         ->orderBy($sort_by, $sort_order)
    //         ->paginate($length)
    //         ->withQueryString();


    // }

    public function download_excel()
    {
        return Excel::download(new TestsExport, 'Resumen Test.xlsx');
    }
}
