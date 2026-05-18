<?php
namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\ApartadoAula;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class HomeController extends Controller
{
    private function semanaOperativa(): string
    {
        $hoy = Carbon::now();
        return $hoy->isSunday()
            ? $hoy->copy()->addDay()->startOfWeek(CarbonInterface::MONDAY)->toDateString()
            : $hoy->copy()->startOfWeek(CarbonInterface::MONDAY)->toDateString();
    }

    private function estadoAula(string $aula, string $dia, string $hora, string $semana): string
    {
        $bloqueada = ApartadoAula::where('aula', $aula)
            ->where('semana_inicio', $semana)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 1)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->exists();

        if ($bloqueada) return 'bloqueada';

        $apartada = ApartadoAula::where('aula', $aula)
            ->where('semana_inicio', $semana)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->exists();

        if ($apartada) return 'apartada';

        $ocupada = Horario::where('aula', $aula)
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->exists();

        return $ocupada ? 'ocupada' : 'libre';
    }

    public function index()
    {
        $ahora        = Carbon::now();
        $semanaActual = $this->semanaOperativa();

        $map = [
            'Monday'    => 'LUNES',
            'Tuesday'   => 'MARTES',
            'Wednesday' => 'MIERCOLES',
            'Thursday'  => 'JUEVES',
            'Friday'    => 'VIERNES',
            'Saturday'  => 'SABADO',
            'Sunday'    => 'DOMINGO',
        ];
        $dia  = $map[$ahora->format('l')];
        $hora = $ahora->format('H:i:s');

        // ── Top eventos ──
        $topLike    = DB::table('eventos')->orderByDesc('likes')->first();
        $topFav     = DB::table('eventos')->orderByDesc('favoritos')->first();
        $topDislike = DB::table('eventos')->orderByDesc('dislikes')->first();

        // ── Aulas Edificio A — KPIs ──
        $prefijo  = 'EA';
        $todasEA = collect(['EA01','EA02','EA03','EA04','EA05','EA07','EA08','EA09','EA10','EAC1','EALQ']);


        $ocupadasEA = Horario::where('aula', 'like', $prefijo . '%')
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula')->unique()->values();

        $apartadasHoyEA = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where('dia', $dia)
            ->where('aula', 'like', $prefijo . '%')
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula')->unique()->values();

        $ocupadasEA    = $ocupadasEA->merge($apartadasHoyEA)->unique()->values();
        $aulasLibres   = $todasEA->diff($ocupadasEA)->count();
        $aulasOcupadas = $todasEA->intersect($ocupadasEA)->count();

        // ── Apartados ──
        $apartadosHoy    = ApartadoAula::where('semana_inicio', $semanaActual)->where('dia', $dia)->count();
        $apartadosSemana = ApartadoAula::where('semana_inicio', $semanaActual)->count();

        // ── Eventos ──
        $eventosHoy      = DB::table('eventos')->whereBetween('fecha_evento', [$ahora->copy()->startOfDay(), $ahora->copy()->endOfDay()])->count();
        $eventosSemana   = DB::table('eventos')->whereBetween('fecha_evento', [$ahora->copy()->startOfWeek()->startOfDay(), $ahora->copy()->endOfWeek()->endOfDay()])->count();
        $eventosProximos = DB::table('eventos')->where('fecha_evento', '>=', $ahora)->count();

        $proximosEventos = DB::table('eventos')
            ->where('fecha_evento', '>=', $ahora)
            ->orderBy('fecha_evento')->limit(4)->get();

        // ── Gráfica eventos últimos 6 meses ──
        $meses = []; $cantidades = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes          = $ahora->copy()->subMonths($i);
            $meses[]      = $mes->translatedFormat('M Y');
            $cantidades[] = DB::table('eventos')
                ->whereBetween('fecha_evento', [$mes->copy()->startOfMonth(), $mes->copy()->endOfMonth()])
                ->count();
        }

        // ── Usuarios y horarios ──
        $totalUsuarios = DB::table('usuarios')->count();
        $totalHorarios = Horario::count();

        // ── Solicitudes ──
        $solicitudesPendientes = DB::table('solicitudes_evento')->where('estado', 'pendiente')->count();
        $solicitudesAprobadas  = DB::table('solicitudes_evento')->where('estado', 'aprobado')->count();
        $solicitudesRechazadas = DB::table('solicitudes_evento')->where('estado', 'rechazado')->count();
        $ultimasSolicitudes    = DB::table('solicitudes_evento')->orderByDesc('created_at')->limit(4)->get();

        // ── Usuarios por rol ──
        $usuariosPorRol = DB::table('usuarios')
            ->selectRaw('rol, COUNT(*) as total')
            ->groupBy('rol')
            ->pluck('total', 'rol')
            ->toArray();

        // ── Estado Edificio A ──
        $aulasConocidas = ['EA01','EA02','EA03','EA04','EA05','EA07','EA08','EA09','EA10','EAC1','EALQ'];
        $estadoAulas = array_map(
            fn($aula) => ['aula' => $aula, 'estado' => $this->estadoAula($aula, $dia, $hora, $semanaActual)],
            $aulasConocidas
        );

        // ── Estado Edificio B ──
        $aulasConocidasB = ['EB01','EB02','EB03','EB04','EB05','EB06','EB07','EB08','ECSI','EBC2','EBRD','EBUM'];
        $estadoAulasB = array_map(
            fn($aula) => ['aula' => $aula, 'estado' => $this->estadoAula($aula, $dia, $hora, $semanaActual)],
            $aulasConocidasB
        );

        // ── Estado Edificio C — Laboratorios ──
        $aulasConocidasC = ['SIMULACION','AUTOMATIZACION','LAB_TIEMPOS_MOV','LAB_METROLOGIA'];
        // Mapeo alias → código DB para revisar horarios
        $mapaC = [
            'SIMULACION'      => 'ECSI',
            'AUTOMATIZACION'  => 'ECAU',
            'LAB_TIEMPOS_MOV' => 'ECTM',
            'LAB_METROLOGIA'  => 'ECMG',
        ];
        $estadoAulasC = [];
        foreach ($aulasConocidasC as $aliasC) {
            $codigoDB = $mapaC[$aliasC];

            $bloqueada = ApartadoAula::where('aula', $aliasC)
                ->where('semana_inicio', $semanaActual)->where('dia', $dia)
                ->where('tipo_bloqueo', 1)
                ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
                ->exists();

            $apartada = ApartadoAula::where('aula', $aliasC)
                ->where('semana_inicio', $semanaActual)->where('dia', $dia)
                ->where('tipo_bloqueo', 0)
                ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
                ->exists();

            $ocupada = Horario::where('aula', $codigoDB)
                ->where('dia', $dia)
                ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
                ->exists();

            $estadoAulasC[] = [
                'aula'   => $aliasC,
                'estado' => $bloqueada ? 'bloqueada' : ($apartada ? 'apartada' : ($ocupada ? 'ocupada' : 'libre')),
            ];
        }

        // ── Estado Manufactura ──
        $aulasManufactura = ['ECTM', 'ECMA', 'ECPL'];

        $bloqueadaM = ApartadoAula::where(fn($q) => $q->where('aula', 'MANUFACTURA')->orWhereIn('aula', $aulasManufactura))
            ->where('semana_inicio', $semanaActual)->where('dia', $dia)
            ->where('tipo_bloqueo', 1)
            ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
            ->exists();

        $apartadaM = ApartadoAula::where(fn($q) => $q->where('aula', 'MANUFACTURA')->orWhereIn('aula', $aulasManufactura))
            ->where('semana_inicio', $semanaActual)->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
            ->exists();

        $ocupadaM = Horario::whereIn('aula', $aulasManufactura)
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)->whereTime('hora_fin', '>', $hora)
            ->exists();

        $estadoManufactura = [[
            'aula'   => 'MANUFACTURA',
            'estado' => $bloqueadaM ? 'bloqueada' : ($apartadaM ? 'apartada' : ($ocupadaM ? 'ocupada' : 'libre')),
        ]];

        return view('home', compact(
            'dia', 'hora',
            'aulasLibres', 'aulasOcupadas',
            'apartadosHoy', 'apartadosSemana',
            'eventosHoy', 'eventosSemana', 'eventosProximos',
            'proximosEventos', 'meses', 'cantidades',
            'totalUsuarios', 'totalHorarios',
            'topLike', 'topFav', 'topDislike',
            'solicitudesPendientes', 'solicitudesAprobadas', 'solicitudesRechazadas',
            'ultimasSolicitudes', 'usuariosPorRol',
            'estadoAulas',
            'estadoAulasB',
            'estadoAulasC',
            'estadoManufactura'
        ));
    }
}