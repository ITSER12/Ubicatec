<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de solicitudes (sin FK formal, referencia manual)
        Schema::create('solicitudes_evento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');   // referencia manual a usuarios
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->dateTime('fechaevento')->nullable();
            $table->string('poster')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamps();
            // ← Sin ->foreign() para evitar conflictos con PK no estándar
        });

        // Agregar columna para rastrear quién ya dio like
        Schema::table('eventos', function (Blueprint $table) {
            $table->json('likes_usuarios')->nullable()->after('likes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_evento');

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('likes_usuarios');
        });
    }
};