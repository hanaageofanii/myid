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
        Schema::create('gcv_pengajuan_dajams', function (Blueprint $table) {
            $table->id();

            $table->string('siteplan')->nullable();
            $table->enum('kavling', ['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('bank')->nullable();
            $table->string('no_debitur')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->enum('nama_dajam',['sertifikat','imb','listrik','jkk','bestek','pph','bphtb'])->nullable();
            $table->string('no_surat')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('nilai_pencairan')->nullable();
            // $table->enum('status_dajam',['sudah_diajukan','belum_diajukan'])->nullable();
            $table->string('up_surat_pengajuan')->nullable();
            $table->json('up_nominatif_pengajuan')->nullable();
            $table->json('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_pengajuan_dajams');
    }
};
