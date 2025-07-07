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
        Schema::create('gcv_rekenings', function (Blueprint $table) {
            $table->id();

            $table->enum('nama_perusahaan', ['langgeng_pertiwi_development','agung_purnama_bakti','purnama_karya_bersama'])->nullable();
            $table->enum('bank',['btn_karawang','btn_cikarang','btn_bekasi','bjb_cikarang','bri_pekayon','bjb_syariah','btn_cibubur','bni_kuningan','mandiri_cikarang'])->nullable();
            $table->enum('jenis',['operasional','escrow'])->nullable();
            $table->string('rekening')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_rekenings');
    }
};
