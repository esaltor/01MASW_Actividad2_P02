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
        Schema::create('AUDITA', function (Blueprint $table) {
            $table->bigIncrements('idAudita');
            $table->timestamp('fechaHora');
            $table->string('accion', 10);

            $table->unsignedBigInteger('idReserva');
            $table->unsignedBigInteger('idUsuario');

            $table->foreign('idReserva')
                ->references('idReserva')->on('RESERVA')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('idUsuario')
                ->references('idUsuario')->on('USUARIO')
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
        Schema::dropIfExists('AUDITA');
    }
};
