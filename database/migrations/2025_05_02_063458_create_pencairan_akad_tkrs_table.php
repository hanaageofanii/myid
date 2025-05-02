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
        Schema::create('pencairan_akad_tkrs', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->enum('bank',['btn_cikarang','btn_bekasi','btn_karawang','bjb_syariah','bjb_jababeka','btn_syariah','brii_bekasi'])->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('max_kpr')->nullable();
            $table->date('tanggal_pencairan')->nullable();
            $table->string('nilai_pencairan')->nullable();
            $table->string('dana_jaminan')->nullable();
            $table->json('up_rekening_koran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_akad_tkrs');
    }
};
