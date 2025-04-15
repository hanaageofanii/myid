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
        Schema::create('rekonsils', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->nullable();
            $table->date('tanggal_transaksi')->nullable();
            $table->string('nama_yang_mencairkan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->string('bank')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('jumlah_uang')->nullable();
            $table->enum('tipe',['debit','kredit'])->nullable();
            $table->enum('status_rekonsil', ['belum','sudah'])->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekonsils');
    }
};
