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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('kasbank');
            $table->date('tanggal');
            $table->string('no_bukti');
            $table->string('up_bukti');
            $table->string('no_cek');
            $table->string('bukti_cek');
            $table->string('pemberi');
            $table->string('catatan');
            $table->string('bukti_bukti');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
