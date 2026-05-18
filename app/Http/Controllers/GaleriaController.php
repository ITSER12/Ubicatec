<?php

namespace App\Http\Controllers;

use App\Models\GaleriaFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class GaleriaController extends Controller
{
    // GET /galeria/fotos?area=xxx
    public function fotos(Request $request)
    {
        $area  = $request->query('area');
        $fotos = GaleriaFoto::where('area', $area)
            ->orderByDesc('created_at')
            ->get()
            ->map(function($f) {
                return [
                    'id'             => $f->id,
                    'titulo'         => $f->titulo,
                    'descripcion'    => $f->descripcion,
                    'imagen_url'     => $f->imagen ? Storage::url($f->imagen) : null,
                    'created_at_human' => $f->created_at->format('d/m/Y'),
                ];
            });

        return response()->json(['fotos' => $fotos]);
    }

    // POST /galeria/fotos
    public function store(Request $request)
    {
        // Solo administrador
        if (!session('usuario') || session('usuario')->rol !== 'administrador') {
            return response()->json(['ok' => false, 'message' => 'Sin permisos'], 403);
        }

        $request->validate([
            'area'        => 'required|string|max:60',
            'titulo'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:500',
            'imagen'      => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $path = $request->file('imagen')->store('galeria', 'public');

        GaleriaFoto::create([
            'area'        => $request->area,
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen'      => $path,
        ]);

        return response()->json(['ok' => true]);
    }

    // DELETE /galeria/fotos/{id}
    public function destroy($id)
    {
        if (!session('usuario') || session('usuario')->rol !== 'administrador') {
            return response()->json(['ok' => false, 'message' => 'Sin permisos'], 403);
        }

        $foto = GaleriaFoto::findOrFail($id);
        if ($foto->imagen) Storage::disk('public')->delete($foto->imagen);
        $foto->delete();

        return response()->json(['ok' => true]);
    }
}