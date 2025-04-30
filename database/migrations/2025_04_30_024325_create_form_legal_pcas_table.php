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
        Schema::create('form_legal_pcas', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan');
            $table->string('nama_konsumen');
            $table->string('id_rumah');
            $table->enum('status_sertifikat', ['induk','pecahan']);
            $table->string('no_sertifikat');
            $table->string('nib');
            $table->string('luas_sertifikat');
            $table->string('imb_pbg');
            $table->string('nop');
            $table->string('nop1');

            $table->json('up_sertifikat')->nullable();
            $table->json('up_pbb')->nullable();
            $table->json('up_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_legal_pcas');
    }
};
