<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\EntregaController;


// Página principal protegida por login
Route::get('/', function () {
    return redirect()->route('menu.principal');
});

// Rutas protegidas por login

    // Menú principal
    Route::get('/menu', function () {
        return view('menu');
    })->name('menu.principal');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Perfiles
    Route::resource('perfiles', PerfilController::class);


// Rutas de autenticación (login, register, etc.)
Auth::routes();



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Página principal (protegida)
/*Route::get('/inicio', function () {
    return view('pagina_principal');
})->name('pagina.principal')->middleware('auth');
;*/

Route::get('/inicio', [HomeController::class, 'index'])->name('inicio');

Route::post('/registro', [AuthController::class, 'register'])->name('registro.store');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('registro.form');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');


Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

/*Route::get('/entregas', function () {
    return 'Página de entregas (en construcción)';
})->name('entregas.index');
*/
Route::get('/reportes', function () {
    return 'Página de reportes (en construcción)';
})->name('reportes.index'); 

  //Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas');

 /*Route::get('/admin/entregas', [EntregaController::class, 'mapaEntregas'])->name('admin.entregas');

 Route::get('/admin/entregas/dividir/{cantidad}', [EntregaController::class, 'dividirEntregas']);*/

/* Route::get('/admin/entregas', [EntregaController::class, 'mapaEntregas'])
    ->name('admin.entregas'); */

    // Generar grupos (POST con cantidad de couriers)
Route::post('/admin/entregas/generar', [EntregaController::class, 'generarRutas'])->name('admin.entregas.generar');

// Ver detalle de un grupo (índice basado en 1: 1..K)
Route::get('/admin/entregas/grupo/{idx}', [EntregaController::class, 'verGrupo'])->name('admin.entregas.grupo');

//-----------------------------------------------------------------

/*Route::get('/admin/entregas/seleccionar', [EntregaController::class, 'selectorMes'])
    ->name('admin.entregas.selector');

// Por ahora solo recibe el mes seleccionado; luego lo conectamos con tu mapa
Route::get('/admin/entregas/mes/{year}/{month}', [EntregaController::class, 'verMes'])
    ->name('admin.entregas.mes');*/

    //-------------------------------------------------------------


// Mapa separado
Route::get('/admin/entregas/mapa', [EntregaController::class, 'mapaEntregas'])
    ->name('admin.entregas.mapa');


Route::get('/admin/entregas/selector', [EntregaController::class, 'selectorMes'])
    ->name('admin.entregas.selector');

    //--------------------------------------
Route::prefix('admin/entregas')->name('admin.entregas.')->group(function () {
    Route::get('/', [EntregaController::class, 'selectorMes'])->name('selector');

    // <-- cambia este handler a mapaPorMes
    Route::get('/mes/{year}/{month}', [EntregaController::class, 'mapaPorMes'])
        ->where(['year' => '\d{4}', 'month' => '0?[1-9]|1[0-2]'])
        ->name('mes');

    Route::post('/generar', [EntregaController::class, 'generarRutas'])->name('generar');
    Route::get('/grupo/{idx}', [EntregaController::class, 'verGrupo'])->name('grupo');
});

// VER GRUPOS DE ENTREGAS Y DEMAS

Route::get('/admin/entregas/gestionar-grupos', [EntregaController::class, 'gestionarGrupos'])
    ->name('admin.entregas.gestionarGrupos');

Route::delete('/admin/entregas/gestionar-grupos/{grupo}', [EntregaController::class, 'eliminarGrupo'])
    ->name('admin.entregas.gestionarGrupos.eliminar');

Route::post('/admin/entregas/gestionar-grupos/{grupo}/asignar-courier', [EntregaController::class, 'asignarCourier'])
    ->name('admin.entregas.gestionarGrupos.asignar'); // placeholder

    Route::post('/admin/entregas/gestionar-grupos/asignaciones',
    [EntregaController::class, 'guardarAsignacionesMasivas']
)->name('admin.entregas.gestionarGrupos.guardarAsignaciones');