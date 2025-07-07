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
        Schema::create('buku_rekonsils', function (Blueprint $table) {
            $table->id();

            $table->enum('nama_perusahaan', ['langgeng_pertiwi_development','agung_purnama_bakti','purnama_karya_bersama'])->nullable();
            $table->string('no_check')->nullable();
            $table->date('tanggal_check')->nullable();
            $table->string('nama_pencair')->nullable();
            $table->date('tanggal_dicairkan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('account_bank')->nullable();
            $table->string('bank')->nullable();
            $table->enum('jenis',['operasional','escrow'])->nullable();
            $table->string('rekening')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('jumlah_uang')->nullable();
            $table->enum('tipe',['debit','kredit'])->nullable();
            $table->string('saldo')->nullable();
            $table->enum('status_disalurkan',['sudah','belum'])->nullable();
            $table->string('catatan')->nullable();
            $table->json('bukti_bukti')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_rekonsils');
    }
};
