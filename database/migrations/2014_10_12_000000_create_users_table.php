<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartado_aulas', function (Blueprint $table) {

            $table->foreign('user_id')
                ->references('no_control')
                ->on('usuarios')
                ->nullOnDelete();

        });
    }
};