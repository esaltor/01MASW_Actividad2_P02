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
        Schema::create('NOTIFICACION', function (Blueprint $table) {
            $table->bigIncrements('idNotificacion');
            $table->string('asunto', 255);
            $table->text('cuerpo');
            $table->string('canal', 50);
            $table->timestamp('enviadaEn')->useCurrent();

            $table->unsignedBigInteger('idUsuario');

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
        Schema::dropIfExists('NOTIFICACION');
    }
};
