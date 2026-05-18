<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventosController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\SolicitudEventoController;
use App\Http\Controllers\RespaldoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ChatSoporteController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS — LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [LoginController::class, 'mostrarLogin']
)->name('login');

Route::post(
    '/login',
    [LoginController::class, 'validarLogin']
)->name('login.post');

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS — 2FA LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/verificar',
    [LoginController::class, 'mostrarVerificarCodigo']
)->name('verificar.codigo');

Route::post(
    '/verificar',
    [LoginController::class, 'verificarCodigo']
)->name('verificar.codigo.post');

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS — REGISTRO
|--------------------------------------------------------------------------
*/

Route::post(
    '/registro',
    [RegistroController::class, 'store']
)->name('registro.post');

Route::post(
    '/registro/verificar',
    [RegistroController::class, 'verificar']
)->name('registro.verificar');

Route::get(
    '/registro/reenviar',
    [RegistroController::class, 'reenviar']
)->name('registro.reenviar');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth.usuario')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [HomeController::class, 'index']
    )->name('home');

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/perfil/datos',
        [PerfilController::class, 'show']
    )->name('perfil.show');

    Route::post(
        '/perfil/update',
        [PerfilController::class, 'update']
    )->name('perfil.update');

    Route::delete(
        '/perfil/{id}',
        [PerfilController::class, 'destroy']
    )->name('perfil.destroy');

    /*
    |--------------------------------------------------------------------------
    | CHAT SOPORTE
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/chat/soporte',
        [ChatSoporteController::class, 'enviar']
    )->name('chat.soporte');

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */

    Route::prefix('usuarios')->group(function () {

        Route::get(
            '/',
            [UsuarioController::class, 'index']
        )->name('usuarios.index');

        Route::get(
            '/api',
            [UsuarioController::class, 'api']
        )->name('usuarios.api');

        Route::get(
            '/{id}/editar',
            [UsuarioController::class, 'editar']
        )->name('usuarios.editar');

        Route::post(
            '/',
            [UsuarioController::class, 'store']
        )->name('usuarios.store');

        Route::put(
            '/{id}',
            [UsuarioController::class, 'update']
        )->name('usuarios.update');

        Route::delete(
            '/{id}',
            [UsuarioController::class, 'destroy']
        )->name('usuarios.destroy');

        Route::post(
            '/{id}/rol',
            [UsuarioController::class, 'updateRol']
        )->name('usuarios.updateRol');
    });

    /*
    |--------------------------------------------------------------------------
    | EVENTOS
    |--------------------------------------------------------------------------
    */

    Route::prefix('eventos')->group(function () {

        Route::get(
            '/',
            [EventosController::class, 'index']
        )->name('eventos.index');

        Route::get(
            '/cartelera',
            [EventosController::class, 'cartelera']
        )->name('eventos.cartelera');

        Route::get(
            '/pdf',
            [EventosController::class, 'generarPDF']
        )->name('eventos.pdf');

        Route::get(
            '/excel',
            [EventosController::class, 'generarExcel']
        )->name('eventos.excel');

        Route::get(
            '/create',
            [EventosController::class, 'create']
        )->name('eventos.create');

        Route::post(
            '/',
            [EventosController::class, 'store']
        )->name('eventos.store');

        Route::get(
            '/{id_evento}/edit',
            [EventosController::class, 'edit']
        )->name('eventos.edit');

        Route::get(
            '/{id_evento}/json',
            [EventosController::class, 'editJson']
        )->name('eventos.edit.json');

        Route::put(
            '/{id_evento}',
            [EventosController::class, 'update']
        )->name('eventos.update');

        Route::delete(
            '/{id_evento}',
            [EventosController::class, 'destroy']
        )->name('eventos.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SOLICITUDES DE EVENTO
    |--------------------------------------------------------------------------
    */

    Route::prefix('solicitudes')->group(function () {

        Route::get(
            '/',
            [SolicitudEventoController::class, 'index']
        )->name('solicitudes.index');

        Route::get(
            '/create',
            [SolicitudEventoController::class, 'create']
        )->name('solicitudes.create');

        Route::post(
            '/',
            [SolicitudEventoController::class, 'store']
        )->name('solicitudes.store');

        Route::post(
            '/{id}/aprobar',
            [SolicitudEventoController::class, 'aprobar']
        )->name('solicitudes.aprobar');

        Route::post(
            '/{id}/rechazar',
            [SolicitudEventoController::class, 'rechazar']
        )->name('solicitudes.rechazar');
    });

    /*
    |--------------------------------------------------------------------------
    | INTERACCIONES EVENTOS (AJAX WEB)
    |--------------------------------------------------------------------------
    */

    Route::prefix('evento')->group(function () {

        Route::post(
            '/like/{id_evento}',
            [EventosController::class, 'like']
        )->name('evento.like');

        Route::post(
            '/dislike/{id_evento}',
            [EventosController::class, 'dislike']
        )->name('evento.dislike');

        Route::post(
            '/favorito/{id_evento}',
            [EventosController::class, 'favorito']
        )->name('evento.favorito');

        Route::get(
            '/comentarios/{id}',
            [EventosController::class, 'comentarios']
        )->name('evento.comentarios');

        Route::post(
            '/comentar/{id}',
            [EventosController::class, 'comentar']
        )->name('evento.comentar');
    });

    /*
    |--------------------------------------------------------------------------
    | AULAS
    |--------------------------------------------------------------------------
    */

    Route::prefix('aulas')->group(function () {

        Route::get(
            '/',
            [AulaController::class, 'index']
        )->name('aulas.index');

        Route::post(
            '/apartar',
            [AulaController::class, 'apartar']
        )->name('aulas.apartar');

        Route::get(
            '/mis-reservaciones',
            [AulaController::class, 'misReservaciones']
        )->name('aulas.misReservaciones');

        Route::get(
            '/edificio-b',
            [AulaController::class, 'edificioB']
        )->name('aulas.edificioB');

        Route::get(
            '/edificio-c',
            [AulaController::class, 'edificioC']
        )->name('aulas.edificioC');

        Route::get(
            '/salones-practicas',
            [AulaController::class, 'salonesPracticas']
        )->name('aulas.salonesPracticas');

        Route::get(
            '/manufactura',
            [AulaController::class, 'manufactura']
        )->name('aulas.manufactura');

        Route::get(
            '/{aula}',
            [AulaController::class, 'show']
        )->name('aulas.show');
    });

    /*
    |--------------------------------------------------------------------------
    | HORARIOS
    |--------------------------------------------------------------------------
    */

    Route::prefix('horarios')->group(function () {

        Route::get(
            '/importar',
            fn () => view('horarios.importar')
        )->name('horarios.importar.form');

        Route::post(
            '/importar',
            [HorariosController::class, 'importarUpload']
        )->name('horarios.importar');
    });

    /*
    |--------------------------------------------------------------------------
    | MAPA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mapa',
        fn () => view('mapa.index')
    )->name('mapa.index');

    /*
    |--------------------------------------------------------------------------
    | GALERÍA
    |--------------------------------------------------------------------------
    */

    Route::prefix('galeria')->group(function () {

        Route::get(
            '/fotos',
            [GaleriaController::class, 'fotos']
        )->name('galeria.fotos');

        Route::post(
            '/fotos',
            [GaleriaController::class, 'store']
        )->name('galeria.store');

        Route::delete(
            '/fotos/{id}',
            [GaleriaController::class, 'destroy']
        )->name('galeria.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | RESPALDO BD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/respaldo/descargar',
        [RespaldoController::class, 'descargar']
    )->name('respaldo.descargar');

    /*
    |--------------------------------------------------------------------------
    | BACKUP
    |--------------------------------------------------------------------------
    */

    Route::prefix('backup')->group(function () {

        Route::get(
            '/',
            [BackupController::class, 'index']
        )->name('backup.index');

        Route::post(
            '/generar',
            [BackupController::class, 'generar']
        )->name('backup.generar');
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD / LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [LoginController::class, 'dashboard']
    )->name('dashboard');

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');

});