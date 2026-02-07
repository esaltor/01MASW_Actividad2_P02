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
        Schema::create('ADJUNTOELEMENTO', function (Blueprint $table) {
            $table->unsignedBigInteger('idAdjunto')->primary();
            $table->unsignedBigInteger('idElemento');

            $table->foreign('idAdjunto')
                ->references('idAdjunto')->on('ADJUNTO')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('idElemento')
                ->references('idElemento')->on('ELEMENTO')
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
        Schema::dropIfExists('ADJUNTOELEMENTO');
    }
};
