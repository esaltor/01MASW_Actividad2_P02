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
        Schema::create('USUARIO', function (Blueprint $table) {
            $table->bigIncrements('idUsuario');
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->unique();

            $table->timestamp('fechaAlta')->useCurrent();
            $table->timestamp('fechaBaja')->nullable();

            $table->unsignedBigInteger('idRol');

            $table->foreign('idRol')
                ->references('idRol')->on('ROL')
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
        Schema::dropIfExists('USUARIO');
    }
};
