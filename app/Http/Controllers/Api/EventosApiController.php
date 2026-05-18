<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Comentario;

class EventosApiController extends Controller
{
    // ===============================
    // GET /api/eventos
    // ===============================
 
public function index()
{
    $eventos = DB::table('eventos')

        ->leftJoin(
            'comentarios',
            'eventos.id_evento',
            '=',
            'comentarios.evento_id'
        )

        ->select(
            'eventos.*',

            DB::raw(
                'COUNT(comentarios.id) as comentarios_count'
            )
        )

        ->groupBy(
            'eventos.id_evento',
            'eventos.titulo',
            'eventos.descripcion',
            'eventos.fecha_evento',
            'eventos.poster',
            'eventos.color_fondo',
            'eventos.color_texto',
            'eventos.borde',
            'eventos.font_family',
            'eventos.size_titulo',
            'eventos.size_desc',
            'eventos.pos_img',
            'eventos.pos_titulo',
            'eventos.pos_desc',
            'eventos.likes',
            'eventos.dislikes',
            'eventos.favoritos',
            'eventos.likes_usuarios',
            'eventos.created_at',
            'eventos.updated_at'
        )

        ->orderBy('fecha_evento', 'asc')

        ->get()

        ->map(function ($evento) {

            $evento->poster_url = $evento->poster
                ? asset('storage/' . $evento->poster)
                : null;

            return $evento;
        });

    return response()->json([
        'status' => 'success',
        'data'   => $eventos,
    ], 200);
}

    // ===============================
    // GET /api/eventos/resumen
    // ===============================
    public function resumen()
    {
        $eventos = DB::table('eventos')
            ->orderBy('fecha_evento', 'asc')
            ->get();

        $eventosTotal = $eventos->count();

        $inicioHoy = now()->startOfDay();
        $finHoy    = now()->endOfDay();

        $eventosHoy = DB::table('eventos')
            ->whereBetween('fecha_evento', [$inicioHoy, $finHoy])
            ->count();

        $inicioSemana = now()->startOfWeek()->startOfDay();
        $finSemana    = now()->endOfWeek()->endOfDay();

        $eventosSemana = DB::table('eventos')
            ->whereBetween('fecha_evento', [$inicioSemana, $finSemana])
            ->count();

        $eventosProximos = DB::table('eventos')
            ->where('fecha_evento', '>=', now())
            ->count();

        $meses = [];
        $cantidades = [];

        for ($i = 0; $i >= -5; $i--) {

            $mes = now()->subMonths($i);

            $meses[] = $mes->format('M');

            $inicioMes = $mes->copy()->startOfMonth()->startOfDay();
            $finMes    = $mes->copy()->endOfMonth()->endOfDay();

            $cantidades[] = DB::table('eventos')
                ->whereBetween('fecha_evento', [$inicioMes, $finMes])
                ->count();
        }

        $eventosFechas = DB::table('eventos')
            ->selectRaw('DATE(fecha_evento) as fecha')
            ->distinct()
            ->orderBy('fecha', 'asc')
            ->pluck('fecha')
            ->toArray();

        $eventosPorFecha = DB::table('eventos')
            ->selectRaw('DATE(fecha_evento) as fecha, id_evento, titulo, fecha_evento, descripcion')
            ->orderBy('fecha_evento', 'asc')
            ->get()
            ->groupBy('fecha');

        return response()->json([
            'status'            => 'success',
            'eventosTotal'      => $eventosTotal,
            'eventosHoy'        => $eventosHoy,
            'eventosSemana'     => $eventosSemana,
            'eventosProximos'   => $eventosProximos,
            'graficaMeses'      => $meses,
            'graficaCantidades' => $cantidades,
            'eventosFechas'     => $eventosFechas,
            'eventosPorFecha'   => $eventosPorFecha,
        ], 200);
    }

    // ===============================
    // POST /api/eventos
    // ===============================
    public function store(Request $request)
    {
        $id = DB::table('eventos')->insertGetId([
            'titulo'       => $request->titulo,
            'fecha_evento' => $request->fecha_evento,
            'descripcion'  => $request->descripcion,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $evento = DB::table('eventos')
            ->where('id_evento', $id)
            ->first();

        return response()->json([
            'status' => 'created',
            'data'   => $evento,
        ], 201);
    }

    // ===============================
    // PUT /api/eventos/{id}
    // ===============================
    public function update(Request $request, $id_evento)
    {
        DB::table('eventos')
            ->where('id_evento', $id_evento)
            ->update([
                'titulo'       => $request->titulo,
                'fecha_evento' => $request->fecha_evento,
                'descripcion'  => $request->descripcion,
                'updated_at'   => now(),
            ]);

        $evento = DB::table('eventos')
            ->where('id_evento', $id_evento)
            ->first();

        return response()->json([
            'status' => 'updated',
            'data'   => $evento,
        ], 200);
    }

    // ===============================
    // DELETE /api/eventos/{id}
    // ===============================
    public function destroy($id_evento)
    {
        DB::table('eventos')
            ->where('id_evento', $id_evento)
            ->delete();

        return response()->json([
            'status'  => 'deleted',
            'message' => 'Evento eliminado correctamente',
        ], 200);
    }

    // ===============================
    // LIKE
    // ===============================
    public function like(Request $request, $id)
    {
        $usuarioId = $request->usuario_id;

        $evento = DB::table('eventos')
            ->where('id_evento', $id)
            ->first();

        if (!$evento) {
            return response()->json([
                'ok' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        $likesUsuarios = json_decode($evento->likes_usuarios ?? '[]', true);

        // SI YA DIO LIKE -> QUITAR LIKE
        if (in_array($usuarioId, $likesUsuarios)) {

            $likesUsuarios = array_values(
                array_filter($likesUsuarios, fn($uid) => $uid != $usuarioId)
            );

            $nuevoTotal = max(0, $evento->likes - 1);

            DB::table('eventos')
                ->where('id_evento', $id)
                ->update([
                    'likes' => $nuevoTotal,
                    'likes_usuarios' => json_encode($likesUsuarios),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'ok'     => true,
                'liked'  => false,
                'likes'  => $nuevoTotal,
            ]);
        }

        // AGREGAR LIKE
        $likesUsuarios[] = $usuarioId;

        $nuevoTotal = $evento->likes + 1;

        DB::table('eventos')
            ->where('id_evento', $id)
            ->update([
                'likes' => $nuevoTotal,
                'likes_usuarios' => json_encode($likesUsuarios),
                'updated_at' => now(),
            ]);

        return response()->json([
            'ok'     => true,
            'liked'  => true,
            'likes'  => $nuevoTotal,
        ]);
    }

    // ===============================
    // FAVORITO
    // ===============================
    public function favorito($id)
    {
        $evento = DB::table('eventos')
            ->where('id_evento', $id)
            ->first();

        if (!$evento) {
            return response()->json([
                'ok' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        $nuevoTotal = $evento->favoritos + 1;

        DB::table('eventos')
            ->where('id_evento', $id)
            ->update([
                'favoritos' => $nuevoTotal,
                'updated_at' => now(),
            ]);

        return response()->json([
            'ok' => true,
            'favoritos' => $nuevoTotal,
        ]);
    }

    // ===============================
    // OBTENER COMENTARIOS
    // ===============================
    public function comentarios($id)
    {
        $comentarios = Comentario::where('evento_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'ok' => true,
            'comentarios' => $comentarios
        ]);
    }

    // ===============================
    // GUARDAR COMENTARIO
    // ===============================
    public function comentar(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:500'
        ]);

        $comentario = Comentario::create([
            'evento_id' => $id,
            'comentario' => $request->comentario
        ]);

        return response()->json([
            'ok' => true,
            'comentario' => $comentario
        ]);
    }
}