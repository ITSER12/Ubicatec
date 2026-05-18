<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventosApiController;
use App\Http\Controllers\Api\AulasApiController;
use App\Http\Controllers\Api\PerfilApiController;
use App\Http\Controllers\Api\GaleriaApiController;
/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// LOGIN + ENVÍO DE CÓDIGO
Route::post(
    '/login',
    [AuthController::class, 'login']
);

// VERIFICAR CÓDIGO
Route::post(
    '/verificar-codigo',
    [AuthController::class, 'verificarCodigo']
);

/*
|--------------------------------------------------------------------------
| EVENTOS PÚBLICOS
|--------------------------------------------------------------------------
*/

// LISTAR EVENTOS
Route::get(
    '/eventos',
    [EventosApiController::class, 'index']
);

// RESUMEN (IMPORTANTE: VA ANTES DE {id})
Route::get(
    '/eventos/resumen',
    [EventosApiController::class, 'resumen']
);

// VER EVENTO POR ID
Route::get(
    '/eventos/{id}',
    [EventosApiController::class, 'show']
);

/*
|--------------------------------------------------------------------------
| INTERACCIONES EVENTOS
|--------------------------------------------------------------------------
*/

// LIKE
Route::post(
    '/eventos/like/{id_evento}',
    [EventosApiController::class, 'like']
);

// DISLIKE
Route::post(
    '/eventos/dislike/{id_evento}',
    [EventosApiController::class, 'dislike']
);

// FAVORITO
Route::post(
    '/eventos/favorito/{id_evento}',
    [EventosApiController::class, 'favorito']
);

// OBTENER COMENTARIOS
Route::get(
    '/eventos/comentarios/{id_evento}',
    [EventosApiController::class, 'comentarios']
);

// AGREGAR COMENTARIO
Route::post(
    '/eventos/comentar/{id_evento}',
    [EventosApiController::class, 'comentar']
);

/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

// OBTENER PERFIL
Route::get(
    '/perfil/{id}',
    [PerfilApiController::class, 'show']
);

// ACTUALIZAR PERFIL
Route::put(
    '/perfil/{id}',
    [PerfilApiController::class, 'update']
);

/*
|--------------------------------------------------------------------------
| AULAS
|--------------------------------------------------------------------------
*/

// MAPA DE AULAS
Route::get(
    '/mapa-aulas',
    [AulasApiController::class, 'mapa']
);

// DETALLE DE AULA
Route::get(
    '/aulas/{aula}',
    [AulasApiController::class, 'show']
);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/me',
        [AuthController::class, 'me']
    );

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    /*
    |--------------------------------------------------------------------------
    | CRUD EVENTOS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/eventos',
        [EventosApiController::class, 'store']
    );

    Route::put(
        '/eventos/{id_evento}',
        [EventosApiController::class, 'update']
    );

    Route::delete(
        '/eventos/{id_evento}',
        [EventosApiController::class, 'destroy']
    );

});
/*
|--------------------------------------------------------------------------
| GALERÍA
|--------------------------------------------------------------------------
*/

Route::get(
    '/galeria',
    [GaleriaApiController::class, 'index']
);

Route::get(
    '/galeria/{id}',
    [GaleriaApiController::class, 'show']
);