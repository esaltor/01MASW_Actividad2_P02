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
        Schema::create('ELEMENTO', function (Blueprint $table) {
            $table->bigIncrements('idElemento');
            $table->string('nombre', 100);
            $table->string('descripcion', 255);
            $table->string('estado', 50);

            $table->unsignedBigInteger('idRecurso')->nullable();

            $table->foreign('idRecurso')
                ->references('idRecurso')->on('RECURSO')
                ->onDelete('set null')
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
        Schema::dropIfExists('ELEMENTO');
    }
};
