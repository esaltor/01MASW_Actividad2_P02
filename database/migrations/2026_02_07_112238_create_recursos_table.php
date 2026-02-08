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
        Schema::create('RECURSO', function (Blueprint $table) {
            $table->bigIncrements('idRecurso');
            $table->string('nombre', 100);
            $table->string('descripcion', 255);
            $table->string('ubicacion', 100);
            $table->string('estado', 50);
            $table->text('caracteristicas')->nullable();

            $table->unsignedBigInteger('idTipoRecurso');

            $table->foreign('idTipoRecurso')
                ->references('idTipoRecurso')->on('TIPORECURSO')
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
        Schema::dropIfExists('RECURSO');
    }
};
