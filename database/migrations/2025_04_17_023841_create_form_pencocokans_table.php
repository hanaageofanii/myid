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
        Schema::create('form_pencocokans', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->nullable();
            $table->string('no_ref_bank')->nullable();
            $table->date('tanggal_transaksi')->nullable();
            $table->string('jumlah')->nullable();
            $table->enum('tipe',['debit', 'kredit'])->nullable();
            $table->enum('status', ['approve','revisi'])->nullable();
            $table->string('nominal_selisih')->nullable();
            $table->string('analisis_selisih')->nullable();
            $table->enum('tindakan',['koreksi', 'pending', 'abaikan'])->nullable();
            $table->date('tanggal_validasi')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->string('catatan')->nullable();
            $table->json('bukti_bukti')->nullable();

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
        Schema::dropIfExists('form_pencocokans');
    }
};
