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
        Schema::create('kartu_kontrol_g_c_v_s', function (Blueprint $table) {
            $table->id();
             $table->enum('proyek', ['gcv_cira','gcv','tkr','tkr_cira','pca1'])->nullable();
            $table->string('lokasi_proyek')->nullable( );
            $table->string('nama_konsumen')->nullable( );
            $table->enum('nama_perusahaan', ['langgeng_pertiwi_development','agung_purnama_bakti','purnama_karya_bersama'])->nullable();
            $table->string('alamat')->nullable( );
            $table->string('no_telepon')->nullable( );
            $table->enum('kavling', ['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('siteplan')->nullable( );
            $table->string('type')->nullable( );
            $table->string('luas')->nullable( );
            $table->date('tanggal_booking')->nullable( );
            $table->string('agent')->nullable( );
            $table->string('bank')->nullable( );
            $table->string('notaris')->nullable( );
            $table->date('tanggal_akad')->nullable( );
            $table->string('harga_jual')->nullable( );
            $table->string('harga/m')->nullable( );
            $table->string('pajak')->nullable( );
            $table->string('biaya_proses')->nullable( );
            $table->string('uang_muka')->nullable( );
            $table->string('estimasi_kpr')->nullable( );
            $table->string('realisasi_kpr')->nullable( );
            $table->string('selisih_kpr')->nullable( );
            $table->string('sbum&disct')->nullable( );
            $table->string('biaya_lain')->nullable( );
            $table->string('total_biaya')->nullable( );
            $table->string('no_konsumen')->nullable( );
            $table->date('tanggal_pembayaran')->nullable( );
            $table->string('keterangan')->nullable( );
            $table->string('nilai_kontrak')->nullable( );
            $table->string('pembayaran')->nullable( );
            $table->string('sisa/saldo')->nullable( );
            $table->string('paraf')->nullable( );
            $table->string('catatan')->nullable( );
            $table->json('bukti_lainnya')->nullable( );
            $table->string('status')->nullable( );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_kontrol_g_c_v_s');
    }
};
