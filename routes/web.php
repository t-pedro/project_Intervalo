<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Manager\Competencia\CompetenciaController;
use App\Http\Controllers\Manager\Afirmation\AfirmationController;
use App\Http\Controllers\Manager\Afirmation\ImportController;
use App\Http\Controllers\Manager\Capsule\CapsuleController;
use App\Http\Controllers\Manager\Category\CategoryController;
use App\Http\Controllers\Manager\Companie\CompanieController;
use App\Http\Controllers\Manager\Dashboard\DashboardController;
use App\Http\Controllers\Manager\Diagnostico\DiagnosticoController;
use App\Http\Controllers\Manager\Sectores\SectorController;
use App\Http\Controllers\Manager\Test\TestController as TestManagerController;
use App\Http\Controllers\Manager\User\UserController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\QuizController;
use App\Http\Controllers\Web\TestController;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//************************** */
// RUTAS PUBLICAS
//************************** */

/* Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('login'); */

Route::get('/test/downloadexcel', [TestManagerController::class, 'download_excel'])->name('test.downloadexcel');
Route::post('/test', [TestController::class, 'store'])->name('test.store');
Route::get('/diagnostico/{id}/users', [TestController::class, 'usersByDiagnostico'])->name('diagnostico.users');

    //Web
Route::get('/', [HomeController::class, 'index'])->name('home');    
Route::get('/quiz/{competencias}', [QuizController::class, 'index'])->name('quiz');  
Route::post('/quiz/calculate', [QuizController::class, 'calculate'])->name('quiz.calculate');        
Route::get('/result', [QuizController::class, 'result'])->name('result');

//************************** */
// RUTAS PRIVADAS
//************************** */
Route::middleware('auth')->group(function () {

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboardDetails', [DashboardController::class, 'details'])->name('dashboard.details');
    
    //Competencias
    Route::get('/competencia', [CompetenciaController::class, 'index'])->name('competencia');
    Route::get('/competencia/list', [CompetenciaController::class, 'list'])->name('competencia.list');
    Route::get('/competencia/create', [CompetenciaController::class, 'create'])->name('competencia.create');
    Route::post('/competencia', [CompetenciaController::class, 'store'])->name('competencia.store');
    Route::get('competencia/{competencia}/edit', [CompetenciaController::class, 'edit'])->name('competencia.edit');  
    Route::put('competencia/{competencia}', [CompetenciaController::class, 'update'])->name('competencia.update');
    Route::delete('competencia/{competencia}', [CompetenciaController::class, 'destroy'])->name('competencia.destroy');
    Route::get('/competencia/import', [CompetenciaController::class, 'import'])->name('competencia.import');
    Route::post('/competencia/importfile', [CompetenciaController::class, 'importfile'])->name('competencia.importfile');   
    Route::post('/competencia/importfilerelated', [CompetenciaController::class, 'importfilerelated'])->name('competencia.importfilerelated'); 
    Route::get('/competencia/autorelacionar', [CompetenciaController::class, 'autorelacionar'])->name('competencia.autorelacionar');   
    
    //Afirmaciones
    Route::get('/afirmation', [AfirmationController::class, 'index'])->name('afirmation');
    Route::get('/afirmation/list', [AfirmationController::class, 'list'])->name('afirmation.list');
    Route::get('/afirmation/create', [AfirmationController::class, 'create'])->name('afirmation.create');
    Route::post('/afirmation', [AfirmationController::class, 'store'])->name('afirmation.store');
    Route::get('/afirmation/import', [ImportController::class, 'import'])->name('afirmation.import');  
    Route::post('/afirmation/importfile', [ImportController::class, 'importfile'])->name('afirmation.importfile');   
    Route::get('afirmation/{afirmation}/edit', [AfirmationController::class, 'edit'])->name('afirmation.edit');  
    Route::put('afirmation/{afirmation}', [AfirmationController::class, 'update'])->name('afirmation.update');
    Route::delete('afirmation/{afirmation}', [AfirmationController::class, 'destroy'])->name('afirmation.destroy');
    
    //Capsulas
    Route::get('/capsule', [CapsuleController::class, 'index'])->name('capsule');
    Route::get('/capsule/create', [CapsuleController::class, 'create'])->name('capsule.create');
    Route::post('/capsule', [CapsuleController::class, 'store'])->name('capsule.store');    
    Route::post('/capsule/{id}/edit', [CapsuleController::class, 'edit'])->name('capsule.edit');   
    Route::get('/capsule/list', [CapsuleController::class, 'list'])->name('capsule.list');
    Route::delete('/capsule/{id}', [CapsuleController::class, 'destroy'])->name('capsule.destroy');
    
    // TEST
    Route::get('/test', [TestManagerController::class, 'index'])->name('test');
    Route::get('/test/list', [TestManagerController::class, 'list'])->name('test.list');
    Route::get('/test/listTest', [TestManagerController::class, 'listTest'])->name('test.listTest');
    Route::post('/testUser', [TestController::class, 'storeUser'])->name('test.storeUser');

    // TEST - DIAGNOSTICOS
    Route::post('/testDiagnostico', [TestController::class, 'storeUserDiagnostico'])->name('test.storeUserDiagnostico');

    // USERS
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');   

    Route::get('/user/import', [UserController::class, 'importView'])->name('user.importView');
    Route::post('/user/import', [UserController::class, 'import'])->name('user.import');
    Route::get('/user/downloadTemplate', [UserController::class, 'donwloadTemplate'])->name('user.donwloadTemplate');

    Route::post('/user/sendResetLink', [UserController::class, 'sendResetLink'])->name('user.sendResetLink');
    Route::get('/user/dataUser', [UserController::class, 'dataUser'])->name('user.dataUser');
    

    // COMPANIES
    Route::get('/company', [CompanieController::class, 'index'])->name('companie');
    Route::get('/company/list', [CompanieController::class, 'list'])->name('companie.list');
    Route::get('/company/create', [CompanieController::class, 'create'])->name('companie.create');
    Route::post('/company', [CompanieController::class, 'store'])->name('companie.store');    

    Route::get('/company/{id}/edit', [CompanieController::class, 'edit'])->name('companie.edit');   
    Route::post('/company/{id}/update', [CompanieController::class, 'update'])->name('companie.update'); 
    //Route::get('/company/{id}/active', [CompanieController::class, 'active'])->name('companie.active');
    //Route::delete('/company/{id}', [CompanieController::class, 'destroy'])->name('companie.destroy');
    Route::get('/company/{id}/users', [CompanieController::class, 'listUserByCompanie'])->name('companie.listUserByCompanie');

    Route::get('/mycompany', [CompanieController::class, 'myCompanie'])->name('mycompanie');
    Route::get('/mycompany/list', [CompanieController::class, 'mylist'])->name('mycompanie.list');

    Route::get('/company/{id}/diagnosticos', [CompanieController::class, 'CompanyDiagnosticosById'])->name('company.diagnosticos');
    Route::get('/company/{id}/sectores', [CompanieController::class, 'CompanySectoresById'])->name('company.sectores');

    Route::prefix('company')->group(function () {
        Route::put('/{id}/active', [CompanieController::class, 'active'])->name('companie.active');
        Route::delete('/{id}', [CompanieController::class, 'destroy'])->name('companie.destroy');
    });

    // ROUTES CATEGORY
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category');
        Route::get('/list', [CategoryController::class, 'list'])->name('category.list');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');   
        Route::put('/{id}/active', [CategoryController::class, 'active'])->name('category.active');
        Route::put('/{id}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    // ROUTES DIAGNOSTICOS
    Route::prefix('diagnosticos')->group(function () {
        Route::get('/', [DiagnosticoController::class, 'index'])->name('diagnostico');
        Route::post('/', [DiagnosticoController::class, 'store'])->name('diagnostico.store');
        Route::put('/{diagnostico}', [DiagnosticoController::class, 'update'])->name('diagnostico.update');
        Route::put('/{diagnostico}/updateEstado', [DiagnosticoController::class, 'updateEstado'])->name('diagnostico.updateEstado');
        Route::delete('/{id}', [DiagnosticoController::class, 'destroy'])->name('diagnostico.destroy');
    });

    // ROUTES SECTORES
    Route::prefix('sectores')->group(function () {
        Route::get('/', [SectorController::class, 'index'])->name('sector');
        Route::post('/', [SectorController::class, 'store'])->name('sector.store');
        Route::put('/{sector}', [SectorController::class, 'update'])->name('sector.update');
        Route::put('/{sector}/active', [SectorController::class, 'active'])->name('sector.active');
        Route::delete('/{id}', [SectorController::class, 'destroy'])->name('sector.destroy');
    });


});

