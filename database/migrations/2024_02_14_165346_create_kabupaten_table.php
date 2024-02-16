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
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 4);
            $table->foreignId('provinsi_id');
            $table->string('nama', 38);
            $table->integer('chart_01')->nullable();
            $table->integer('chart_02')->nullable();
            $table->integer('chart_03')->nullable();
            $table->integer('progress_proses');
            $table->integer('progress_total');
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabupaten');
    }
};
