<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SolicitudEvento;
use Carbon\Carbon;

class SolicitudEventoController extends Controller
{
    // Lista solicitudes pendientes (solo admin/jefe)
    public function index()
    {
        $solicitudes = SolicitudEvento::with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cpanel.solicitudes.index', compact('solicitudes'));
    }

    // Formulario para docente/estudiante
    public function create()
    {
        return view('cpanel.solicitudes.create');
    }

    // Guardar solicitud
    public function store(Request $request)
{
    $request->validate([
        'titulo'      => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'fechaevento' => 'nullable|date',
        'poster'      => 'nullable|image|max:4096',
    ]);

    // ✅ Leer el no_control del usuario en sesión
    $usuario   = session('usuario');
    $usuarioId = is_array($usuario)
                    ? ($usuario['no_control'] ?? null)   // array
                    : ($usuario->no_control ?? null);    // objeto

    if (!$usuarioId) {
        return redirect()->route('login')
            ->withErrors(['auth' => 'Sesión expirada. Inicia sesión de nuevo.']);
    }

    $ruta = null;
    if ($request->hasFile('poster')) {
        $archivo = $request->file('poster');
        $nombre  = time() . '_' . $archivo->getClientOriginalName();
        $ruta    = $archivo->storeAs('solicitudes', $nombre, 'public');
    }

    SolicitudEvento::create([
        'usuario_id'  => $usuarioId,          // guarda el no_control
        'titulo'      => $request->titulo,
        'descripcion' => $request->descripcion,
        'fechaevento' => $request->fechaevento
            ? Carbon::parse($request->fechaevento)->format('Y-m-d H:i:s')
            : null,
        'poster'      => $ruta,
        'estado'      => 'pendiente',
    ]);

    return redirect()->route('eventos.index')
        ->with('success', '¡Solicitud enviada! Un administrador la revisará pronto.');
}

    // Admin aprueba → crea el evento real
    public function aprobar($id)
{
    $sol = SolicitudEvento::findOrFail($id);

    DB::table('eventos')->insert([
        'titulo'         => $sol->titulo,
        'descripcion'    => $sol->descripcion,
        'fecha_evento'   => $sol->fechaevento ?? now(),
        'poster'         => $sol->poster,
        'color_fondo'    => '#ffffff',
        'color_texto'    => '#1e3040',
        'borde'          => '3px solid #376073',
        'pos_img'        => 'center',
        'pos_titulo'     => 'top',
        'pos_desc'       => 'bottom',
        'font_family'    => 'Montserrat',
        'size_titulo'    => 36,
        'size_desc'      => 30,
        'likes'          => 0,
        'dislikes'       => 0,
        'favoritos'      => 0,
        'likes_usuarios' => '[]',
        'created_at'     => now(),
        'updated_at'     => now(),
    ]);

    $sol->update(['estado' => 'aprobado']);

    return back()->with('success', 'Evento aprobado y publicado.');
}

    // Admin rechaza
    public function rechazar($id)
    {
        SolicitudEvento::findOrFail($id)->update(['estado' => 'rechazado']);
        return back()->with('success', 'Solicitud rechazada.');
    }
}