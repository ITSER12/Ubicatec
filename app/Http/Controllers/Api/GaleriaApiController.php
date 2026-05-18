<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GaleriaFoto;

class GaleriaApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR FOTOS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $fotos = GaleriaFoto::orderByDesc('created_at')
            ->get()
            ->map(function ($foto) {

                return [
                    'id'          => $foto->id,
                    'area'        => $foto->area,
                    'titulo'      => $foto->titulo,
                    'descripcion' => $foto->descripcion,

                    'imagen_url'  => asset(
                        'storage/' . $foto->imagen
                    ),

                    'fecha'       => optional($foto->created_at)
                        ->format('d/m/Y'),
                ];
            });

        return response()->json([
            'ok'   => true,
            'data' => $fotos,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO INDIVIDUAL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $foto = GaleriaFoto::find($id);

        if (!$foto) {

            return response()->json([
                'ok'      => false,
                'message' => 'Foto no encontrada',
            ], 404);
        }

        return response()->json([
            'ok' => true,

            'data' => [
                'id'          => $foto->id,
                'area'        => $foto->area,
                'titulo'      => $foto->titulo,
                'descripcion' => $foto->descripcion,

                'imagen_url'  => asset(
                    'storage/' . $foto->imagen
                ),

                'fecha'       => optional($foto->created_at)
                    ->format('d/m/Y'),
            ]
        ]);
    }
}