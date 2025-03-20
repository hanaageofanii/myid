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
        Schema::create('ajbs', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->string('nop')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('suket_validasi')->nullable();
            $table->string('no_sspd_bphtb')->nullable();
            $table->string('tanggal_sspd_bphtb')->nullable();
            $table->string('no_validasi_sspd_bphtb')->nullable();
            $table->string('notaris')->nullable();
            $table->string('no_ajb')->nullable();
            $table->date('tanggal_ajb')->nullable();
            $table->string('no_bast')->nullable();
            $table->string('tanggal_bast')->nullable();
            $table->string('up_bast')->nullable();
            $table->string('up_validasi_bphtb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajbs');
    }
};
