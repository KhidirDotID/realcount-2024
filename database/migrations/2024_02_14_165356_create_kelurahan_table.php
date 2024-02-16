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
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10);
            $table->foreignId('kecamatan_id');
            $table->string('nama', 100);
            $table->integer('chart_01')->nullable();
            $table->integer('chart_02')->nullable();
            $table->integer('chart_03')->nullable();
            $table->integer('progress_proses');
            $table->integer('progress_total');
            $table->timestamps();

            $table->foreign('kecamatan_id')->references('id')->on('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};
