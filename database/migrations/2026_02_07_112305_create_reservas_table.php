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
        Schema::create('RESERVA', function (Blueprint $table) {
            $table->bigIncrements('idReserva');
            $table->string('estado', 50);
            $table->date('fecha');
            $table->timestamp('fechaCreacion')->useCurrent();

            $table->unsignedBigInteger('idSesion')->nullable();
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idRecurso');

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('USUARIO')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('idRecurso')
                ->references('idRecurso')->on('RECURSO')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('idSesion')
                ->references('idSesion')->on('SESION')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('fecha')
                ->references('fecha')->on('CALENDARIO')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RESERVA');
    }
};
