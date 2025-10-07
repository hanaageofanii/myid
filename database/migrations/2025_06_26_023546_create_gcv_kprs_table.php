<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Team;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gcv_kprs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->index();
            $table->enum('jenis_unit',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('siteplan');
            $table->enum('type',['29/60','30/60','45/104','32/52','36/60','36/72'])->nullable();
            $table->decimal('luas', 10, 2)->nullable();
            $table->string('agent')->nullable();
            $table->date('tanggal_booking')->nullable();
            $table->date('tanggal_akad')->nullable();
            $table->string('harga')->nullable();
            $table->string('maksimal_kpr')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_email')->nullable();
            $table->enum('pembayaran',['kpr','cash','cash_bertahap','promo'])->nullable();
            $table->enum('bank',['btn_cikarang','btn_bekasi','btn_karawang','bjb_syariah','bjb_jababeka','btn_syariah','brii_bekasi'])->nullable();
            $table->string('no_rekening')->nullable();
            $table->enum('status_akad',['akad','batal'])->nullable();

            // Upload data
            $table->json('data_diri')->nullable();
            $table->boolean('ktp')->nullable();
            $table->boolean('kk')->nullable();
            $table->boolean('npwp_upload')->nullable();
            $table->boolean('buku_nikah')->nullable();
            $table->boolean('akte_cerai')->nullable();
            $table->boolean('akte_kematian')->nullable();
            $table->boolean('kartu_bpjs')->nullable();
            $table->boolean('drk')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_kprs');
    }
};