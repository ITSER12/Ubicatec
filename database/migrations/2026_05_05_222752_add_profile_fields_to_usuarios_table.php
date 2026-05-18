<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->string('foto')->nullable()->after('correo');
        $table->text('descripcion')->nullable()->after('foto');
        $table->enum('rol', [
            'administrador',
            'docente',
            'jefe_departamento',
            'estudiante'
        ])->default('estudiante')->after('descripcion');
    });
}

public function down(): void
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->dropColumn(['foto', 'descripcion', 'rol']);
    });
}
};
