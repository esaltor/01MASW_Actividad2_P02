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
        Schema::create('INCIDENCIA', function (Blueprint $table) {
            $table->bigIncrements('idIncidencia');
            $table->string('titulo', 100);
            $table->string('descripcion', 255);
            $table->string('estado', 50);

            $table->unsignedBigInteger('idTipoIncidencia');
            $table->unsignedBigInteger('idElemento');
            $table->unsignedBigInteger('idUsuario');

            $table->foreign('idTipoIncidencia')
                ->references('idTipoIncidencia')->on('TIPOINCIDENCIA')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('idElemento')
                ->references('idElemento')->on('ELEMENTO')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('USUARIO')
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
        Schema::dropIfExists('INCIDENCIA');
    }
};
