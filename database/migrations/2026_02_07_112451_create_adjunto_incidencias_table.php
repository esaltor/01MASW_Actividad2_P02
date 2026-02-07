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
        Schema::create('ADJUNTOINCIDENCIA', function (Blueprint $table) {
            $table->unsignedBigInteger('idAdjunto')->primary();
            $table->unsignedBigInteger('idIncidencia');

            $table->foreign('idAdjunto')
                ->references('idAdjunto')->on('ADJUNTO')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('idIncidencia')
                ->references('idIncidencia')->on('INCIDENCIA')
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
        Schema::dropIfExists('ADJUNTOINCIDENCIA');
    }
};
