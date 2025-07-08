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
        Schema::create('gcv_pengajuan_bns', function (Blueprint $table) {
            $table->id();

            $table->enum('kavling', ['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('siteplan')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('luas')->nullable();

            $table->string('harga_jual')->nullable();
            $table->string('tanggal_lunas')->nullable();
            $table->string('nop')->nullable();
            $table->string('nama_notaris')->nullable();

            $table->string('pph')->nullable();
            $table->string('bphtb')->nullable();
            $table->string('ppn')->nullable();
            $table->string('biaya_notaris')->nullable();
            $table->string('adm_bphtb')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('status_bn',['sudah','belum'])->nullable();

            $table->json('up_dokumen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_pengajuan_bns');
    }
};
