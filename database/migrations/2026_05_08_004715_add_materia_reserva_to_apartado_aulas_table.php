<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartado_aulas', function (Blueprint $table) {
            // Se agrega después de nombre_solicitante
            $table->string('materia_reserva', 150)->nullable()->after('nombre_solicitante');
        });
    }

    public function down(): void
    {
        Schema::table('apartado_aulas', function (Blueprint $table) {
            $table->dropColumn('materia_reserva');
        });
    }
};