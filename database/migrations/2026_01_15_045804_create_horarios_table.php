<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/2026_01_15_045804_create_horarios_table.php
public function up(): void
{
    // Cambia Schema::create por Schema::createIfNotExists
    Schema::whenTableDoesntHaveColumn('horarios', 'id', function () {
        // No hacer nada
    });

    if (!Schema::hasTable('horarios')) {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('materia');
            $table->string('grupo');
            $table->string('aula');
            $table->enum('dia', ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('docente')->nullable();
            $table->timestamps();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
