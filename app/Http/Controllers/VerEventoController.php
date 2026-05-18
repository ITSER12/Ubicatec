<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VerEventoController extends Controller
{
    // ===============================
    // LISTAR EVENTOS
    // ===============================
    public function index()
    {
        $eventos = DB::table('eventos')->orderBy('fecha_evento','asc')->get();
        return view('cpanel.ver_eventos', compact('eventos'));
    }

    // ===============================
    // API EVENTOS
    // ===============================
    public function api()
    {
        $eventos = DB::table('eventos')->orderBy('fecha_evento','asc')->get();
        return response()->json($eventos);
    }

    // ===============================
    // EDITAR EVENTO
    // ===============================
    public function edit($id)
    {
        $evento = DB::table('eventos')->where('id_evento',$id)->first();
        return response()->json($evento);
    }

    // ===============================
    // ACTUALIZAR EVENTO
    // ===============================
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'titulo','descripcion','fecha_evento','color_fondo','color_texto',
            'borde','font_family','size_titulo','size_desc','pos_img','pos_titulo','pos_desc'
        ]);

        if($request->hasFile('poster')){
            $poster = $request->file('poster')->store('posters','public');
            $data['poster'] = $poster;
        }

        DB::table('eventos')->where('id_evento',$id)->update($data);

        $evento = DB::table('eventos')->where('id_evento',$id)->first();

        return response()->json(['ok'=>true,'evento'=>$evento]);
    }

    // ===============================
    // ELIMINAR EVENTO
    // ===============================
    public function destroy($id)
    {
        $evento = DB::table('eventos')->where('id_evento',$id)->first();

        if($evento){
            if($evento->poster){
                Storage::disk('public')->delete($evento->poster);
            }

            // 🔥 BORRA TAMBIÉN COMENTARIOS
            DB::table('comentarios')->where('evento_id',$id)->delete();

            DB::table('eventos')->where('id_evento',$id)->delete();
        }

        return response()->json(['ok'=>true]);
    }

    // ===============================
    // INTERACCIONES
    // ===============================
    public function like($id)
    {
        DB::table('eventos')->where('id_evento',$id)->increment('likes');
        return response()->json(['ok'=>true]);
    }

    public function dislike($id)
    {
        DB::table('eventos')->where('id_evento',$id)->increment('dislikes');
        return response()->json(['ok'=>true]);
    }

    public function favorito($id)
    {
        DB::table('eventos')->where('id_evento',$id)->increment('favoritos');
        return response()->json(['ok'=>true]);
    }

    // ===============================
    // CREAR EVENTO
    // ===============================
    public function store(Request $request)
    {
        $data = $request->only([
            'titulo','descripcion','fecha_evento','color_fondo','color_texto',
            'borde','font_family','size_titulo','size_desc','pos_img','pos_titulo','pos_desc'
        ]);

        if($request->hasFile('poster')){
            $poster = $request->file('poster')->store('posters','public');
            $data['poster'] = $poster;
        }

        $id = DB::table('eventos')->insertGetId($data);
        $evento = DB::table('eventos')->where('id_evento',$id)->first();

        return response()->json(['ok'=>true,'evento'=>$evento]);
    }

    // ===============================
    // 🔥 COMENTARIOS
    // ===============================

    // Obtener comentarios de un evento
    public function comentarios($id)
    {
        $comentarios = DB::table('comentarios')
            ->where('evento_id',$id)
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($comentarios);
    }

    // Guardar comentario
    public function guardarComentario(Request $request, $id)
    {
        DB::table('comentarios')->insert([
            'evento_id' => $id,
            'comentario' => $request->comentario
        ]);

        return response()->json(['ok'=>true]);
    }
}