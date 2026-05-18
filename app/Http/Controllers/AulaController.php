<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\ApartadoAula;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    private const DIA_MAP = [
        'Monday'    => 'LUNES',
        'Tuesday'   => 'MARTES',
        'Wednesday' => 'MIERCOLES',
        'Thursday'  => 'JUEVES',
        'Friday'    => 'VIERNES',
        'Saturday'  => 'SABADO',
        'Sunday'    => 'DOMINGO',
    ];

    private function semanaOperativa(): string
    {
        $hoy = Carbon::now();

        return $hoy->isSunday()
            ? $hoy->copy()->addDay()->startOfWeek(CarbonInterface::MONDAY)->toDateString()
            : $hoy->copy()->startOfWeek(CarbonInterface::MONDAY)->toDateString();
    }

    public function index(Request $request)
    {
        
        $prefijo = $request->get('prefijo', 'EA');
        $ahora = Carbon::now();

        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');
        $semanaActual = $this->semanaOperativa();

        ApartadoAula::where('semana_inicio', '<', $semanaActual)->delete();

        $todas = Horario::where('aula', 'like', $prefijo . '%')
            ->distinct()
            ->orderBy('aula')
            ->pluck('aula');

        $ocupadas = Horario::where('aula', 'like', $prefijo . '%')
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula')
            ->unique()
            ->values();

        $apartadasHoy = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula')
            ->unique()
            ->values();

        $ocupadas = $ocupadas->merge($apartadasHoy)->unique()->values();
        $libres = $todas->diff($ocupadas)->values();

        $horarios = Horario::where('aula', 'like', $prefijo . '%')
            ->orderBy('aula')
            ->orderBy('dia')
            ->orderBy('hora_inicio')
            ->get(['aula', 'dia', 'hora_inicio', 'hora_fin', 'grupo', 'materia', 'docente']);

        $apartados = ApartadoAula::where('semana_inicio', $semanaActual)->get();

        return view('aulas.index', compact(
            'prefijo',
            'dia',
            'hora',
            'todas',
            'ocupadas',
            'libres',
            'horarios',
            'apartados',
            'semanaActual'
        ));
    }

    public function apartar(Request $request)
    {
        
        $request->validate([
            'aula'              => 'required|string',
            'dia'               => 'required|string',
            'hora_inicio'       => 'required|date_format:H:i,H:i:s',
            'hora_fin'          => 'required|date_format:H:i,H:i:s|after:hora_inicio',
            'nombre_solicitante'=> 'nullable|string|max:150',
            'materia_reserva'   => 'nullable|string|max:150',
            'override'          => 'nullable|in:0,1',
            'tipo_bloqueo'      => 'nullable|in:0,1',
        ]);

        $user = session('usuario');
        abort_unless($user, 403);

        $ahora = Carbon::now();
        $semanaActual = $this->semanaOperativa();
        $diaUpper = strtoupper($request->dia);
        $esOverride = $request->override == '1';
        $esBloqueo = $request->tipo_bloqueo == '1';

        $rol = $user->rol ?? 'estudiante';
        $puedeOverride = in_array($rol, ['administrador', 'jefe_departamento']);
        $puedeReservar = in_array($rol, ['administrador', 'jefe_departamento', 'docente']);

        if (!$puedeReservar) {
            return back()->with('error', 'No tienes permisos para realizar reservas.');
        }

        if (($esOverride || $esBloqueo) && !$puedeOverride) {
            return back()->with('error', 'No tienes permisos para esta acción.');
        }

        $horaInicioNorm = substr($request->hora_inicio, 0, 5);
        $horaFinNorm = substr($request->hora_fin, 0, 5);

        $inicio = Carbon::createFromFormat('H:i', $horaInicioNorm);
        $fin = Carbon::createFromFormat('H:i', $horaFinNorm);
        $minutos = $inicio->diffInMinutes($fin);

        if (!$esBloqueo && $rol === 'docente' && $minutos > 120) {
            return back()->with('error', 'El apartado no puede exceder 2 horas.');
        }

        if (!$esOverride) {
            $conflictoClase = Horario::where('aula', $request->aula)
                ->where('dia', $diaUpper)
                ->where(function ($q) use ($horaInicioNorm, $horaFinNorm) {
                    $q->whereTime('hora_inicio', '<', $horaFinNorm)
                      ->whereTime('hora_fin', '>', $horaInicioNorm);
                })
                ->exists();

            if ($conflictoClase) {
                return back()->with('error', 'Ya existe una clase programada en ese horario.');
            }

            $conflictoApartado = ApartadoAula::where('aula', $request->aula)
                ->where('dia', $diaUpper)
                ->where('semana_inicio', $semanaActual)
                ->where(function ($q) use ($horaInicioNorm, $horaFinNorm) {
                    $q->whereTime('hora_inicio', '<', $horaFinNorm)
                      ->whereTime('hora_fin', '>', $horaInicioNorm);
                })
                ->exists();

            if ($conflictoApartado) {
                return back()->with('error', 'Ese horario ya fue apartado esta semana.');
            }
        }

        $nombreSolicitante = $request->nombre_solicitante ?? ($user->nombre ?? null);
        $materiaReserva = $request->materia_reserva ?? null;

        
        ApartadoAula::create([
            'aula'              => strtoupper($request->aula),
            'dia'               => $diaUpper,
            'hora_inicio'       => $horaInicioNorm . ':00',
            'hora_fin'          => $horaFinNorm . ':00',
            'nombre_solicitante'=> $nombreSolicitante,
            'materia_reserva'   => $materiaReserva,
            'semana_inicio'     => $semanaActual,
            'tipo_bloqueo'      => $esBloqueo ? 1 : 0,
            'override'          => $esOverride ? 1 : 0,
            'usuario_no_control' => $user->no_control ?? null,
        ]);

        if ($esBloqueo) {
            return back()->with('success', "'{$request->aula}' bloqueada el {$diaUpper} de {$horaInicioNorm} a {$horaFinNorm}.");
        }

        return back()->with('success', "Aula {$request->aula} reservada el {$diaUpper} de {$horaInicioNorm} a {$horaFinNorm}.");
    }

    public function show($aula)
    {
        $ahora = Carbon::now();
        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');

        $actual = Horario::where('aula', $aula)
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->orderBy('hora_inicio')
            ->first();

        $proxima = Horario::where('aula', $aula)
            ->where('dia', $dia)
            ->where('hora_inicio', '>', $hora)
            ->orderBy('hora_inicio')
            ->first();

        $hoy = Horario::where('aula', $aula)
            ->where('dia', $dia)
            ->orderBy('hora_inicio')
            ->get();

        return view('aulas.show', compact('aula', 'dia', 'hora', 'actual', 'proxima', 'hoy'));
    }

    public function edificioB()
    {
        $ahora = Carbon::now();
        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');
        $semanaActual = $this->semanaOperativa();

        ApartadoAula::where('semana_inicio', '<', $semanaActual)->delete();

        $aulasExtra = ['ECSI', 'EBC2', 'EBRD', 'EBUM'];

        $horarios = Horario::where('aula', 'like', 'EB%')
            ->orWhereIn('aula', $aulasExtra)
            ->orderBy('aula')
            ->orderBy('dia')
            ->orderBy('hora_inicio')
            ->get(['aula', 'dia', 'hora_inicio', 'hora_fin', 'grupo', 'materia', 'docente']);

        $apartados = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where(function ($q) use ($aulasExtra) {
                $q->where('aula', 'like', 'EB%')
                  ->orWhereIn('aula', $aulasExtra);
            })
            ->get();

        return view('aulas.edificio-b', compact(
            'horarios',
            'dia',
            'hora',
            'apartados',
            'semanaActual'
        ));
    }

    public function edificioC()
    {
        $ahora = Carbon::now();
        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');
        $semanaActual = $this->semanaOperativa();

        ApartadoAula::where('semana_inicio', '<', $semanaActual)->delete();

        $mapaAulasC = [
            'SIMULACION'     => 'ECSI',
            'AUTOMATIZACION' => 'ECAU',
            'LAB_TIEMPOS_MOV'=> 'ECTM',
            'LAB_METROLOGIA' => 'ECMG',
        ];

        $aulasDB = array_values($mapaAulasC);
        $dbToMapa = array_flip($mapaAulasC);

        $horarios = Horario::whereIn('aula', $aulasDB)
            ->orderBy('aula')
            ->orderBy('dia')
            ->orderBy('hora_inicio')
            ->get(['aula', 'dia', 'hora_inicio', 'hora_fin', 'grupo', 'materia', 'docente'])
            ->map(function ($h) use ($dbToMapa) {
                $h->aula_original = $h->aula;
                $h->aula = $dbToMapa[$h->aula] ?? $h->aula;
                return $h;
            });

        $apartados = ApartadoAula::where('semana_inicio', $semanaActual)
            ->whereIn('aula', array_keys($mapaAulasC))
            ->get();

        $ocupadas = $horarios->filter(function ($h) use ($dia, $hora) {
            return $h->dia === $dia
                && $h->hora_inicio <= $hora
                && $h->hora_fin > $hora;
        })->pluck('aula')->unique()->values();

        $libres = collect(array_keys($mapaAulasC))->diff($ocupadas)->values();

        return view('aulas.edificio-c', compact(
            'horarios',
            'dia',
            'hora',
            'apartados',
            'semanaActual',
            'ocupadas',
            'libres'
        ));
    }

    public function salonesPracticas()
    {
        $ahora = Carbon::now();
        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');

        $horarios = collect();
        $apartados = collect();

        return view('aulas.salones-prac', compact('horarios', 'apartados', 'dia', 'hora'));
    }

    public function manufactura()
    {
        $ahora = Carbon::now();
        $dia = self::DIA_MAP[$ahora->format('l')] ?? 'LUNES';
        $hora = $ahora->format('H:i:s');
        $semanaActual = $this->semanaOperativa();

        ApartadoAula::where('semana_inicio', '<', $semanaActual)->delete();

        $aulasManufactura = ['ECTM', 'ECMA', 'ECPL'];

        $horarios = Horario::whereIn('aula', $aulasManufactura)
            ->orderBy('aula')
            ->orderBy('dia')
            ->orderBy('hora_inicio')
            ->get(['aula', 'dia', 'hora_inicio', 'hora_fin', 'grupo', 'materia', 'docente'])
            ->map(function ($h) {
                return [
                    'aula'          => 'MANUFACTURA',
                    'aula_original'  => $h->aula,
                    'dia'           => $h->dia,
                    'hora_inicio'   => $h->hora_inicio,
                    'hora_fin'      => $h->hora_fin,
                    'grupo'         => $h->grupo,
                    'materia'       => $h->materia,
                    'docente'       => $h->docente,
                ];
            })
            ->values();

        $apartados = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where(function ($q) use ($aulasManufactura) {
                $q->where('aula', 'MANUFACTURA')
                  ->orWhereIn('aula', $aulasManufactura);
            })
            ->get()
            ->map(function ($a) {
                return [
                    'aula'              => 'MANUFACTURA',
                    'aula_original'     => $a->aula,
                    'dia'               => $a->dia,
                    'hora_inicio'       => $a->hora_inicio,
                    'hora_fin'          => $a->hora_fin,
                    'nombre_solicitante'=> $a->nombre_solicitante,
                    'materia_reserva'   => $a->materia_reserva,
                    'tipo_bloqueo'      => $a->tipo_bloqueo,
                    'override'          => $a->override,
                    'semana_inicio'     => $a->semana_inicio,
                ];
            })
            ->values();

        $ocupadas = $horarios->filter(function ($h) use ($dia, $hora) {
            return $h['dia'] === $dia
                && $h['hora_inicio'] <= $hora
                && $h['hora_fin'] > $hora;
        })->pluck('aula')->unique()->values();

        $ocupadasApartadas = $apartados->filter(function ($a) use ($dia, $hora) {
            return $a['dia'] === $dia
                && (int) $a['tipo_bloqueo'] === 0
                && $a['hora_inicio'] <= $hora
                && $a['hora_fin'] > $hora;
        })->pluck('aula')->unique()->values();

        $ocupadas = $ocupadas->merge($ocupadasApartadas)->unique()->values();
        $libres = collect(['MANUFACTURA'])->diff($ocupadas)->values();

        return view('aulas.manufactura', compact(
            'horarios',
            'dia',
            'hora',
            'apartados',
            'semanaActual',
            'ocupadas',
            'libres'
        ));
    }

    public function misReservaciones()
{
    $user = session('usuario');
    abort_unless($user, 403);

    $rol = $user->rol ?? 'estudiante';
    abort_unless(in_array($rol, ['administrador', 'jefe_departamento', 'docente']), 403);

    $noControl = $user->no_control ?? null;
    $semanaActual = $this->semanaOperativa();

    $reservas = ApartadoAula::where('usuario_no_control', $noControl)
        ->where('tipo_bloqueo', 0)
        ->orderByDesc('semana_inicio')
        ->orderByRaw("FIELD(dia, 'LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO')")
        ->orderBy('hora_inicio')
        ->get([
            'id',
            'aula',
            'dia',
            'hora_inicio',
            'hora_fin',
            'nombre_solicitante',
            'materia_reserva',
            'semana_inicio',
            'override',
            'usuario_no_control',
        ]);

    $reservasSemana = $reservas->where('semana_inicio', $semanaActual)->values();

    return response()->json([
        'ok' => true,
        'semana' => $semanaActual,
        'reservas' => $reservasSemana,
    ]);
}
}