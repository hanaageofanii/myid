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
        Schema::create('g_c_v_s', function (Blueprint $table) {
            $table->id();
            $table->enum('proyek', ['gcv_cira','gcv','tkr','pca1'])->nullable();
            $table->enum('nama_perusahaan', ['grand_cikarang_village','taman_kertamukti_residence','pesona_cengkong_asri_1'])->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('siteplan')->nullable();
            $table->string('type')->nullable();
            $table->integer('luas_tanah')->nullable();
            $table->enum('status',['booking','indent','ready'])->nullable();
            $table->date('tanggal_booking')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('agent')->nullable();
            $table->enum('kpr_status', ['sp3k','akad','batal'])->nullable();
            $table->date('tanggal_akad')->nullable();
            $table->text('ket')->nullable();
            $table->string('user')->nullable();
            $table->date('tanggal_update')->nullable();
            $table->enum('status_sertifikat',['pecah','belum']);
            $table->enum('status_pembayaran',['cash','cash_bertahap','kpr','promo']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_c_v_s');
    }
};
