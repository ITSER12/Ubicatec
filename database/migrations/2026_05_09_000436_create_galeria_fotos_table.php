<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeria_fotos', function (Blueprint $table) {
            $table->id();
            $table->string('area', 60);        // cancha-futbol, a, b, c, practicas, etc.
            $table->string('titulo', 120);
            $table->text('descripcion')->nullable();
            $table->string('imagen');          // ruta en storage/app/public/galeria/
            $table->timestamps();

            $table->index('area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeria_fotos');
    }
};