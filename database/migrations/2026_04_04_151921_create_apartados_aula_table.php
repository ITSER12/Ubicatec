<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_apartados_aula_table.php
        public function up(): void
        {
            Schema::create('apartados_aula', function (Blueprint $table) {
                $table->id();
                $table->string('aula');
                $table->string('dia');          // LUNES, MARTES...
                $table->time('hora_inicio');
                $table->time('hora_fin');
                $table->string('nombre_solicitante')->nullable();
                $table->date('semana_inicio');  // Lunes de la semana en que se apartó
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartados_aula');
    }

    
};
