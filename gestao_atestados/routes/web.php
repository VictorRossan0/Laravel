<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoAtestadoController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ExcelCSVController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Login, Registro e  Logout

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware('auth')->group(function () {

    // Tipo de Atestado
    Route::resource('tipo_atestado', TipoAtestadoController::class);
    Route::get('/tipo_atestado/{tipoAtestado}/file', [TipoAtestadoController::class, 'showFile'])->name('tipo_atestado.file');
    Route::get('/tipo_atestado/{id}/aprovar', [TipoAtestadoController::class, 'aprovar'])->name('tipo_atestado.aprovar');
    Route::get('/tipo_atestado/{id}/reprovar', [TipoAtestadoController::class, 'showReprovarForm'])->name('tipo_atestado.reprovar');
    Route::post('/tipo_atestado/{id}/reprovar', [TipoAtestadoController::class, 'reprovar'])->name('tipo_atestado.doReprovar');
    Route::post('/tipo_atestado/delete-selected', [TipoAtestadoController::class, 'deleteSelected'])->name('tipo_atestado.delete-selected');
    Route::get('/absenteismo-equipe', [TipoAtestadoController::class, 'calcularAbsenteismoEquipe'])->name('absenteismo.equipe');
    Route::get('/tipo-atestados', [TipoAtestadoController::class, 'puxarDadosTipoAtestados']);


    // Colaboradores
    Route::resource('colaboradores', ColaboradorController::class)->parameters(['colaboradores' => 'colaborador']);
    Route::post('/colaboradores/delete-selected', [ColaboradorController::class, 'deleteSelected'])->name('colaboradores.delete-selected');

    // Cadastro em lote
    Route::get('excel-csv-file', [ExcelCSVController::class, 'index'])->name('cadastro.lote');
    Route::post('import-excel-csv-file', [ExcelCSVController::class, 'importExcelCSV'])->name('import.excel');
    Route::get('export-excel-csv-file/{slug}', [ExcelCSVController::class, 'exportExcelCSV'])->name('export.excel');

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    Route::get('/gerente/home', [HomeController::class, 'gerenteHome'])->name('gerente.home');

    //Users
    Route::resource('users', UserController::class);

});
