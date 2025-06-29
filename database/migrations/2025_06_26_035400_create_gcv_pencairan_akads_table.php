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
        Schema::create('gcv_pencairan_akads', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->enum('bank',['btn_cikarang','btn_bekasi','btn_karawang','bjb_syariah','bjb_jababeka','btn_syariah','brii_bekasi'])->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('max_kpr')->nullable();
            $table->date('tanggal_pencairan')->nullable();
            $table->string('nilai_pencairan')->nullable();
            $table->string('dana_jaminan')->nullable();
            $table->string('no_debitur')->nullable();
            $table->enum('status_pembayaran',['cash','cash_bertahap','kpr','promo'])->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->json('up_spd5')->nullable();
            $table->json('up_rekening_koran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_pencairan_akads');
    }
};