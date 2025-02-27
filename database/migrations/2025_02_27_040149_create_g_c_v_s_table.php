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
            $table->enum('proyek', ['gcv_cir','gcv','tkr','pca1']);
            $table->enum('nama_perusahaan', ['grand_cikarang_village','taman_kertamukti_residence','pesona_cengkong_asri_1']);
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios']);
            $table->unsignedBigInteger('siteplan');
            $table->foreign('siteplan')->references('id')->on('audits')->onDelete('cascade');
            $table->string('type');
            $table->integer('luas_tanah');
            $table->enum('status',['booking','indent','ready'])->nullable();
            $table->date('tanggal_booking')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('agent')->nullable();
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
