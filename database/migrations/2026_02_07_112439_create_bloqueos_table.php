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
        Schema::create('BLOQUEO', function (Blueprint $table) {
            $table->unsignedBigInteger('idRecurso');
            $table->unsignedTinyInteger('diaSemana');
            $table->unsignedBigInteger('idSesion');
            $table->text('motivoBloqueo')->nullable();

            $table->primary(['idRecurso', 'diaSemana', 'idSesion']);

            $table->foreign('idRecurso')
                ->references('idRecurso')->on('RECURSO')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('idSesion')
                ->references('idSesion')->on('SESION')
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
        Schema::dropIfExists('BLOQUEO');
    }
};
