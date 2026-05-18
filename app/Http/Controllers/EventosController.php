<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventosExport;
use Carbon\Carbon;
use App\Models\Comentario;

class EventosController extends Controller
{

    // ===============================
    // LISTAR EVENTOS (ADMIN + STATS)
    // ===============================
    public function index()
    {
        $eventos = DB::table('eventos')
    ->select(
        'id_evento',
        'titulo',
        'descripcion',
        'fecha_evento',
        'poster',
        'color_fondo',
        'color_texto',
        'borde',
        'font_family',
        'size_titulo',
        'size_desc',
        'pos_img',
        'pos_titulo',
        'pos_desc',
        'likes',
        'dislikes',
        'favoritos',
        'likes_usuarios'
    )
    ->get();

        $eventosPorFecha = DB::table('eventos')
        ->selectRaw('DATE(fecha_evento) as fecha_key, id_evento, titulo, descripcion, fecha_evento, poster')
        ->orderBy('created_at', 'asc')
        ->get()
        ->groupBy('fecha_key')
        ->map(fn($grupo) => $grupo->values())  // ← fuerza índices numéricos
        ->toArray();                            // ← convierte a array puro PHP

        $meses = [];
        $cantidadesMes = [];

        for ($i = 1; $i <= 12; $i++) {
            $mes = Carbon::create()->month($i);
            $meses[] = $mes->format('M');

            $cantidadesMes[] = DB::table('eventos')
                ->whereMonth('fecha_evento', $i)
                ->whereYear('fecha_evento', Carbon::now()->year)
                ->count();
        }

        $dias = [];
        $cantidadesDia = [];
        $diasMes = Carbon::now()->daysInMonth;

        for ($i = 1; $i <= $diasMes; $i++) {
            $dias[] = $i;

            $cantidadesDia[] = DB::table('eventos')
                ->whereDay('fecha_evento', $i)
                ->whereMonth('fecha_evento', Carbon::now()->month)
                ->count();
        }

        $diasSemana = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
        $cantidadesSemana = [];

        foreach ($diasSemana as $index => $dia) {
            $cantidadesSemana[] = DB::table('eventos')
                ->whereRaw('WEEKDAY(fecha_evento) = ?', [$index])
                ->count();
        }

        $topLike = DB::table('eventos')->orderByDesc('likes')->first();
        $topDislike = DB::table('eventos')->orderByDesc('dislikes')->first();
        $topFav = DB::table('eventos')->orderByDesc('favoritos')->first();
        return view('cpanel.eventos.eventos', compact(
            'eventos',
            'eventosPorFecha',
            'meses',
            'cantidadesMes',
            'dias',
            'cantidadesDia',
            'diasSemana',
            'cantidadesSemana',
            'topLike',
            'topDislike',
            'topFav'
        ));
    }

    // ===============================
    // CARTELERA
    // ===============================
    public function cartelera()
    {
        $eventos = DB::table('eventos')
            ->orderBy('fecha_evento', 'asc')
            ->get();

        return view('cpanel.Eventoscartelera', compact('eventos'));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        return view('cpanel.crear_evento');
    }

    // ===============================
    // STORE (CORREGIDO)
    // ===============================
    public function store(Request $request)
    {
        try {

            $rutaImagen = null;

            if ($request->hasFile('poster')) {
                $archivo = $request->file('poster');
                $nombre = time() . '_' . $archivo->getClientOriginalName();
                $rutaImagen = $archivo->storeAs('posters', $nombre, 'public');
            }

            // Construir el borde correctamente con grosor, estilo y color
            $grosor = $request->grosor_borde ?? 3;
            $estilo = $request->estilo_borde ?? 'solid';
            $color = $request->color_borde ?? '#000000';
            $borde = $grosor . 'px ' . $estilo . ' ' . $color;

            DB::table('eventos')->insert([
                'titulo'       => $request->titulo ?? '',
                'fecha_evento' => $request->fechaevento
                                    ? Carbon::parse($request->fechaevento)->format('Y-m-d H:i:s')
                                    : now(),
                'descripcion'  => $request->descripcion,
                'color_fondo'  => $request->colorfondo,
                'color_texto'  => $request->colortexto,
                'borde'        => $borde,
                'poster'       => $rutaImagen,
                'pos_img'      => $request->posimg,
                'pos_titulo'   => $request->postitulo,
                'pos_desc'     => $request->posdesc,
                'font_family'  => $request->fontfamily,
                'size_titulo'  => $request->sizetitulo,
                'size_desc'    => $request->sizedesc,
                'likes'        => 0,
                'dislikes'     => 0,
                'favoritos'    => 0,
                'likes_usuarios' => json_encode([]),
                'created_at'     => now(),   
                'updated_at'     => now(),
            ]);

            return redirect()->route('eventos.index')
                ->with('success', 'Evento creado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el evento: ' . $e->getMessage());
        }
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $evento = DB::table('eventos')
            ->where('id_evento', $id)
            ->first();

        return view('cpanel.editar_evento', compact('evento'));
    }

    // ===============================
    // UPDATE (CORREGIDO)
    // ===============================
    public function update(Request $request, $id)
    {
        try {

            $evento = DB::table('eventos')->where('id_evento', $id)->first();

            if (!$evento) {
                return redirect()->route('eventos.index')
                    ->with('error', 'Evento no encontrado');
            }

            $rutaImagen = $evento->poster;

            if ($request->hasFile('poster')) {

                if ($evento->poster && Storage::disk('public')->exists($evento->poster)) {
                    Storage::disk('public')->delete($evento->poster);
                }

                $archivo = $request->file('poster');
                $nombre = time() . '_' . $archivo->getClientOriginalName();

                $rutaImagen = $archivo->storeAs('posters', $nombre, 'public');
            }

            // Construir el borde correctamente con grosor, estilo y color
            $grosor = $request->grosor_borde ?? 3;
            $estilo = $request->estilo_borde ?? 'solid';
            $color = $request->color_borde ?? '#000000';
            $borde = $grosor . 'px ' . $estilo . ' ' . $color;

            DB::table('eventos')->where('id_evento', $id)->update([
            'titulo'       => $request->titulo,
            'fecha_evento' => $request->fechaevento
                                ? Carbon::parse($request->fechaevento)->format('Y-m-d H:i:s')
                                : now(),
            'descripcion'  => $request->descripcion,
            'color_fondo'  => $request->colorfondo,
            'color_texto'  => $request->colortexto,
            'borde'        => $borde,
            'poster'       => $rutaImagen,
            'pos_img'      => $request->posimg,
            'pos_titulo'   => $request->postitulo,
            'pos_desc'     => $request->posdesc,
            'font_family'  => $request->fontfamily,
            'size_titulo'  => $request->sizetitulo,
            'size_desc'    => $request->sizedesc,
        ]);

            return redirect()->route('eventos.index')
                ->with('success', 'Evento actualizado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el evento: ' . $e->getMessage());
        }
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy($id)
    {
        try {
            $evento = DB::table('eventos')->where('id_evento', $id)->first();
            if ($evento && $evento->poster) {
                Storage::disk('public')->delete($evento->poster);
            }
            DB::table('eventos')->where('id_evento', $id)->delete();

            // Responde JSON si es petición AJAX (desde el blade JS)
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['ok' => true]);
            }
            return redirect()->back()->with('success', 'Evento eliminado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    // ===============================
    // PDF
    // ===============================
    public function generarPDF()
    {
        $eventos = DB::table('eventos')->get();
        $pdf = Pdf::loadView('cpanel.eventos.pdf', compact('eventos'));

        return $pdf->download('reporte-eventos.pdf');
    }

    // ===============================
    // EXCEL
    // ===============================
    public function generarExcel()
    {
        return Excel::download(new EventosExport(), 'reporte-eventos.xlsx');
    }

    // ===============================
    // INTERACCIONES
    // ===============================
    public function like($id)
{
    $usuarioId = session('usuario')['no_control'] ?? null;
    $evento = DB::table('eventos')->where('id_evento', $id)->first();
    if (!$evento) return response()->json(['ok' => false], 404);

    $yaLike = json_decode($evento->likes_usuarios ?? '[]', true);

    if (in_array($usuarioId, $yaLike)) {
        $yaLike = array_values(array_filter($yaLike, fn($uid) => $uid !== $usuarioId));
        DB::table('eventos')->where('id_evento', $id)->update([
            'likes'          => max(0, $evento->likes - 1),
            'likes_usuarios' => json_encode($yaLike),
        ]);
        return response()->json(['ok' => true, 'liked' => false, 'likes' => max(0, $evento->likes - 1)]);
    }

    $yaLike[] = $usuarioId;
    DB::table('eventos')->where('id_evento', $id)->update([
        'likes'          => $evento->likes + 1,
        'likes_usuarios' => json_encode($yaLike),
    ]);
    return response()->json(['ok' => true, 'liked' => true, 'likes' => $evento->likes + 1]);
}

    // DISLIKE
    public function dislike($id)
    {
        DB::table('eventos')->where('id_evento', $id)->increment('dislikes');
        return response()->json(['ok' => true]);
    }

    // FAVORITO
    public function favorito($id)
    {
        DB::table('eventos')->where('id_evento', $id)->increment('favoritos');
        return response()->json(['ok' => true]);
    }

    // ===============================
// COMENTARIOS
// ===============================

// 🔥 OBTENER COMENTARIOS
public function comentarios($id)
{
    return Comentario::where('evento_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();
}

// 🔥 GUARDAR COMENTARIO
public function comentar(Request $request, $id)
{
    Comentario::create([
        'evento_id' => $id,
        'comentario' => $request->comentario
    ]);

    return response()->json(['ok' => true]);
}


}