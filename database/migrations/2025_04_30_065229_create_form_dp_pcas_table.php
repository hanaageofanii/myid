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
        Schema::create('form_dp_pcas', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('harga')->nullable();
            $table->string('max_kpr')->nullable();

            $table->string('sbum')->nullable();
            $table->string('sisa_pembayaran')->nullable();
            $table->string('dp')->nullable();
            $table->string('laba_rugi')->nullable();
            $table->date('tanggal_terima_dp')->nullable();
            $table->enum('pembayaran', ['cash','potong_komisi','promo'])->nullable();

            $table->json('up_kwitansi')->nullable();
            $table->json('up_pricelist')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_dp_pcas');
    }
};
