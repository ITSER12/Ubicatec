<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('apartado_aulas', 'usuario_no_control')) {
            Schema::table('apartado_aulas', function (Blueprint $table) {
                $table->string('usuario_no_control')->nullable()->after('user_id');
            });
        }

        Schema::table('apartado_aulas', function (Blueprint $table) {
            $table->foreign('usuario_no_control')
                ->references('no_control')
                ->on('usuarios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('apartado_aulas', function (Blueprint $table) {
            $table->dropForeign(['usuario_no_control']);
        });

        if (Schema::hasColumn('apartado_aulas', 'usuario_no_control')) {
            Schema::table('apartado_aulas', function (Blueprint $table) {
                $table->dropColumn('usuario_no_control');
            });
        }
    }
};