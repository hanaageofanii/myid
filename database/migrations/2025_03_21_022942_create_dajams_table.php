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
        Schema::create('dajams', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->enum('bank',['btn_cikarang','btn_bekasi','btn_karawang','bjb_syariah','bjb_jababeka','btn_syariah','brii_bekasi'])->nullable();
            $table->string('no_debitur')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('max_kpr')->nullable();
            $table->string('nilai_pencairan')->nullable();
            $table->string('total_dajam')->nullable();
            $table->string('dajam_sertifikat')->nullable();
            $table->string('dajam_imb')->nullable();
            $table->string('dajam_listrik')->nullable();
            $table->string('dajam_jkk')->nullable();
            $table->string('dajam_bestek')->nullable();
            $table->string('jumlah_realisasi_dajam')->nullable();
            $table->string('dajam_pph')->nullable();
            $table->string('dajam_bphtb')->nullable();
            $table->string('pembukuan')->nullable();

            $table->json('up_spd5')->nullable();
            $table->json('up_lainnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dajams');
    }
};
