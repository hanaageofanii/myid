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
        Schema::create('rekening_korans', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->nullable();
            $table->string('tanggal_mutasi')->nullable();
            $table->string('keterangan_dari_bank')->nullable();
            $table->string('nominal')->nullable();
            $table->enum('tipe', ['debit', 'kredit'])->nullable();
            $table->string('saldo')->nullable();
            $table->string('no_referensi_bank')->nullable();
            $table->string('bank')->nullable();
            $table->string('catatan')->nullable();
            $table->json('up_rekening_koran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_korans');
    }
};
