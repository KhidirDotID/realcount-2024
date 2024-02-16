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
            $table->string('kode', 13);
            $table->foreignId('kelurahan_id');
            $table->string('nama', 100);
            $table->integer('chart_01')->nullable();
            $table->integer('chart_02')->nullable();
            $table->integer('chart_03')->nullable();
            $table->integer('suara_sah')->nullable();
            $table->integer('suara_tidak_sah')->nullable();
            $table->integer('suara_total')->nullable();
            $table->integer('pemilih_dpt_laki')->nullable();
            $table->integer('pemilih_dpt_perempuan')->nullable();
            $table->integer('pemilih_dpt_jumlah')->nullable();
            $table->integer('pengguna_dpt_laki')->nullable();
            $table->integer('pengguna_dpt_perempuan')->nullable();
            $table->integer('pengguna_dpt_jumlah')->nullable();
            $table->integer('pengguna_dptb_laki')->nullable();
            $table->integer('pengguna_dptb_perempuan')->nullable();
            $table->integer('pengguna_dptb_jumlah')->nullable();
            $table->integer('pengguna_dpk_laki')->nullable();
            $table->integer('pengguna_dpk_perempuan')->nullable();
            $table->integer('pengguna_dpk_jumlah')->nullable();
            $table->integer('pengguna_total_laki')->nullable();
            $table->integer('pengguna_total_perempuan')->nullable();
            $table->integer('pengguna_total_jumlah')->nullable();
            $table->string('images_x1', 140)->nullable();
            $table->string('images_x2', 140)->nullable();
            $table->string('images_x3', 140)->nullable();
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
