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
        Schema::create('HISTORIAL', function (Blueprint $table) {
            $table->bigIncrements('idHistorial');
            $table->date('fecha');
            $table->time('horaInicio');
            $table->time('horaFin');

            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idRecurso');

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('USUARIO')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('idRecurso')
                ->references('idRecurso')->on('RECURSO')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HISTORIAL');
    }
};
