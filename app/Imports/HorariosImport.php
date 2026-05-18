<?php 

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HorariosImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $materia = trim((string)($row['matnom'] ?? ''));
            $grupo   = trim((string)($row['gpocve'] ?? ''));

            if ($materia === '' || $grupo === '') {
                continue;
            }

            $dias = [
                'LUNES'     => ['hra' => 'lunhra', 'aul' => 'lunaul'],
                'MARTES'    => ['hra' => 'marhra', 'aul' => 'maraul'],
                'MIERCOLES' => ['hra' => 'miehra', 'aul' => 'mieaul'],
                'JUEVES'    => ['hra' => 'juehra', 'aul' => 'jueaul'],
                'VIERNES'   => ['hra' => 'viehra', 'aul' => 'vieaul'],
                'SABADO'    => ['hra' => 'sabhra', 'aul' => 'sabaul'],
            ];

            foreach ($dias as $dia => $cols) {
                $hra = trim((string)($row[$cols['hra']] ?? '')); // ej: 09001100
                $aul = trim((string)($row[$cols['aul']] ?? '')); // ej: EB05

                if ($hra === '' || $aul === '') continue;

                [$inicio, $fin] = $this->parseHoraRango($hra);

                DB::table('horarios')->insert([
                    'materia' => $materia,
                    'grupo' => $grupo,
                    'aula' => $aul,          // EA05, EB09, E209, ECSI...
                    'dia' => $dia,           // LUNES, MARTES...
                    'hora_inicio' => $inicio,
                    'hora_fin' => $fin,
                    'docente' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function parseHoraRango(string $hra): array
    {
        // 09001100 => 09:00:00 - 11:00:00
        $h1 = substr($hra, 0, 2); $m1 = substr($hra, 2, 2);
        $h2 = substr($hra, 4, 2); $m2 = substr($hra, 6, 2);
        return ["$h1:$m1:00", "$h2:$m2:00"];
    }
}

