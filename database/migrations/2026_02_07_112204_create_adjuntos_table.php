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
        Schema::create('ADJUNTO', function (Blueprint $table) {
            $table->bigIncrements('idAdjunto');
            $table->string('nombre', 100);
            $table->string('mimeType', 100);
            $table->integer('tamBytes');
            $table->string('url', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ADJUNTO');
    }
};
