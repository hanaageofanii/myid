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
        Schema::create('cek_perjalanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_ref_bank')->nullable();
            $table->string('no_transaksi')->nullable();
            $table->string('nama_pencair')->nullable();
            $table->date('tanggal_dicairkan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->string('tujuan_dana')->nullable();
            $table->enum('status_disalurkan',['sudah', 'belum'])->nullable();
            $table->json('bukti_pendukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_perjalanans');
    }
};
