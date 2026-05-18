<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\ApartadoAula;
use Carbon\Carbon;

class AulasApiController extends Controller
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

    // =====================================
    // MAPA GENERAL
    // =====================================

    public function mapa()
    {
        $ahora = Carbon::now();

        $dia = self::DIA_MAP[$ahora->format('l')];

        $hora = $ahora->format('H:i:s');

        $semanaActual = $ahora
            ->copy()
            ->startOfWeek()
            ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | EDIFICIO A
        |--------------------------------------------------------------------------
        */

        $aulasA = Horario::where('aula', 'like', 'EA%')
            ->distinct()
            ->orderBy('aula')
            ->pluck('aula');

        $ocupadasA = Horario::where('aula', 'like', 'EA%')
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula');

        $apartadasA = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->where('aula', 'like', 'EA%')
            ->pluck('aula');

        $ocupadasA = $ocupadasA
            ->merge($apartadasA)
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | EDIFICIO B
        |--------------------------------------------------------------------------
        */

        $extrasB = [
            'ECSI',
            'EBC2',
            'EBRD',
            'EBUM',
        ];

        $aulasB = Horario::where('aula', 'like', 'EB%')
            ->orWhereIn('aula', $extrasB)
            ->distinct()
            ->orderBy('aula')
            ->pluck('aula');

        $ocupadasB = Horario::where(function ($q) use ($extrasB) {

                $q->where('aula', 'like', 'EB%')
                  ->orWhereIn('aula', $extrasB);
            })
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula');

        $apartadasB = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->where(function ($q) use ($extrasB) {

                $q->where('aula', 'like', 'EB%')
                  ->orWhereIn('aula', $extrasB);
            })
            ->pluck('aula');

        $ocupadasB = $ocupadasB
            ->merge($apartadasB)
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | EDIFICIO C
        |--------------------------------------------------------------------------
        */

        $mapaAulasC = [
            'SIMULACION'      => 'ECSI',
            'AUTOMATIZACION'  => 'ECAU',
            'LAB_TIEMPOS_MOV' => 'ECTM',
            'LAB_METROLOGIA'  => 'ECMG',
        ];

        $aulasC = collect(array_keys($mapaAulasC));

        $ocupadasCDB = Horario::whereIn(
                'aula',
                array_values($mapaAulasC)
            )
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->pluck('aula');

        $ocupadasC = $ocupadasCDB
            ->map(function ($aulaDB) use ($mapaAulasC) {

                return array_search($aulaDB, $mapaAulasC);
            })
            ->filter()
            ->unique()
            ->values();

        $apartadasC = ApartadoAula::where('semana_inicio', $semanaActual)
            ->where('dia', $dia)
            ->where('tipo_bloqueo', 0)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->whereIn('aula', array_keys($mapaAulasC))
            ->pluck('aula');

        $ocupadasC = $ocupadasC
            ->merge($apartadasC)
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | FORMATO FINAL
        |--------------------------------------------------------------------------
        */

        $edificioA = $aulasA->map(function ($aula) use ($ocupadasA) {

            return [
                'nombre' => $aula,

                'estado' => $ocupadasA->contains($aula)
                    ? 'OCUPADA'
                    : 'LIBRE',
            ];
        });

        $edificioB = $aulasB->map(function ($aula) use ($ocupadasB) {

            return [
                'nombre' => $aula,

                'estado' => $ocupadasB->contains($aula)
                    ? 'OCUPADA'
                    : 'LIBRE',
            ];
        });

        $edificioC = $aulasC->map(function ($aula) use ($ocupadasC) {

            return [
                'nombre' => $aula,

                'estado' => $ocupadasC->contains($aula)
                    ? 'OCUPADA'
                    : 'LIBRE',
            ];
        });

        return response()->json([

            'status' => 'success',

            'dia' => $dia,

            'hora' => $hora,

            'edificios' => [

                [
                    'nombre' => 'Edificio A',

                    'codigo' => 'EA',

                    'aulas' => $edificioA,
                ],

                [
                    'nombre' => 'Edificio B',

                    'codigo' => 'EB',

                    'aulas' => $edificioB,
                ],

                [
                    'nombre' => 'Edificio C',

                    'codigo' => 'EC',

                    'aulas' => $edificioC,
                ],
            ]
        ]);
    }

    // =====================================
    // DETALLE DE AULA
    // =====================================

    public function show($aula)
    {
        $ahora = Carbon::now();

        $dia = self::DIA_MAP[$ahora->format('l')];

        $hora = $ahora->format('H:i:s');

        /*
        |--------------------------------------------------------------------------
        | MAPEO EDIFICIO C
        |--------------------------------------------------------------------------
        */

        $mapaAulasC = [
            'SIMULACION'      => 'ECSI',
            'AUTOMATIZACION'  => 'ECAU',
            'LAB_TIEMPOS_MOV' => 'ECTM',
            'LAB_METROLOGIA'  => 'ECMG',
        ];

        $aulaDB = $mapaAulasC[$aula] ?? $aula;

        $actual = Horario::where('aula', $aulaDB)
            ->where('dia', $dia)
            ->whereTime('hora_inicio', '<=', $hora)
            ->whereTime('hora_fin', '>', $hora)
            ->orderBy('hora_inicio')
            ->first();

        $proxima = Horario::where('aula', $aulaDB)
            ->where('dia', $dia)
            ->where('hora_inicio', '>', $hora)
            ->orderBy('hora_inicio')
            ->first();

        $hoy = Horario::where('aula', $aulaDB)
            ->where('dia', $dia)
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([

            'status' => 'success',

            'aula' => $aula,

            'aula_db' => $aulaDB,

            'dia' => $dia,

            'hora' => $hora,

            'actual' => $actual,

            'proxima' => $proxima,

            'hoy' => $hoy,
        ]);
    }
}