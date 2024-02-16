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
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelurahan_id');
            $table->string('nama', 100);
            $table->integer('chart_01')->nullable();
            $table->integer('chart_02')->nullable();
            $table->integer('chart_03')->nullable();
            $table->integer('suara_sah');
            $table->integer('suara_total');
            $table->integer('pengguna_j');
            $table->integer('pemilih_j');
            $table->string('images_x1', 100);
            $table->string('images_x2', 100);
            $table->timestamps();

            $table->foreign('kelurahan_id')->references('id')->on('kelurahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};
