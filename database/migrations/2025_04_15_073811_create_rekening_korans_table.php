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
            $table->string('no_transaksi');
            $table->string('tanggal_mutasi');
            $table->string('keterangan_dari_bank');
            $table->string('nominal');
            $table->enum('tipe', ['debit', 'kredit']);
            $table->string('saldo');
            $table->string('no_referensi_bank');
            $table->string('bank');
            $table->string('catatan');
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
