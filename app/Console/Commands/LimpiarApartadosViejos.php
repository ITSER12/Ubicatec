<?php
namespace App\Console\Commands;

use App\Models\ApartadoAula;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LimpiarApartadosViejos extends Command
{
    protected $signature   = 'apartados:limpiar';
    protected $description = 'Elimina apartados de semanas anteriores a la actual';

    public function handle(): void
    {
        $semanaActual = Carbon::now()->startOfWeek()->toDateString();

        $eliminados = ApartadoAula::where('semana_inicio', '<', $semanaActual)->delete();

        $this->info("✅ Apartados eliminados: {$eliminados}");
    }
}