<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartado_aulas', function (Blueprint $table) {
            $table->id();
            $table->string('aula');
            $table->string('dia');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('nombre_solicitante')->nullable();
            $table->date('semana_inicio');
            $table->tinyInteger('tipo_bloqueo')->default(0);
            $table->tinyInteger('override')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartado_aulas');
    }
};