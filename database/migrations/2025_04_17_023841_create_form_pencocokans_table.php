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
            $table->string('no_transaksi');
            $table->string('no_ref_bank');
            $table->date('tanggal_transaksi');
            $table->string('jumlah');
            $table->enum('tipe',['debit', 'kredit']);
            $table->enum('status', ['sudah','belum']);
            $table->string('selisih');
            $table->string('catatan');
            $table->json('bukti_bukti');
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
