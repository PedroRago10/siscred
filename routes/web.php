<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register' => false]);

Route::get('/relatorios/geral', [App\Http\Controllers\HomeController::class, 'index'])->name('relatorios-geral');
Route::get('/relatorios/cidades', [App\Http\Controllers\HomeController::class, 'IncomesByCities'])->name('relatorios-cidades');
Route::get('/relatorios/clientes', [App\Http\Controllers\HomeController::class, 'IncomesByClients'])->name('relatorios-clientes');
Route::get('/relatorios/servicos', [App\Http\Controllers\HomeController::class, 'IncomesByServices'])->name('relatorios-servicos');
Route::get('/ordens-de-servico/import', [App\Http\Controllers\ServiceOrderController::class, 'import'])->name('ordens-de-servico.import');
Route::post('/ordens-de-servico/importTxt', [App\Http\Controllers\ServiceOrderController::class, 'importTxt'])->name('ordens-de-servico.importTxt');
Route::patch('/ordens-de-servico/updateOS/{id}/{type}/{value}', [App\Http\Controllers\ServiceOrderController::class, 'updateOS'])->name('ordens-de-servico.updateOS');

Route::get('/settings', [App\Http\Controllers\UserSettingsController::class, 'edit'])->name('settings.edit');
Route::patch('/settings' ,[App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
Route::get('/change-password', [App\Http\Controllers\UserSettingsController::class, 'editPass'])->name('settings.change-password');
Route::patch('/change-password', [App\Http\Controllers\UserSettingsController::class, 'updatePass'])->name('settings.change-password-update');


Route::get('/crawler', [App\Http\Controllers\CrawlerController::class, 'index']);


Route::resources([
    'usuarios' => UserController::class,
    'cidades'=> CityController::class,
    'clientes' => ClientController::class,
    'servicos' => ServiceController::class,
    'ordens-de-servico' => ServiceOrderController::class,
    'despesas' => ExpenseController::class
]);



