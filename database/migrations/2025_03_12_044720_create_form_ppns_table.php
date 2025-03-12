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
        Schema::create('form_ppns', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->enum('kavling', ['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_seri_faktur')->nullable();
            $table->date('tanggal_faktur')->nullable();
            $table->string('harga_jual')->nullable();
            $table->string('dpp_ppn')->nullable();
            $table->string('tarif_ppn')->nullable();
            $table->string('jumlah_ppn')->nullable();
            $table->enum('status_ppn', ['dtp','dtp_sebagian','dibebaskan','bayar'])->nullable(); 
            $table->date('tanggal_bayar_ppn')->nullable();
            $table->string('ntpn_ppn')->nullable();

            $table->string('up_bukti_setor_ppn')->nullable()->nullable();
            $table->string('up_efaktur')->nullable()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_ppns');
    }
};
