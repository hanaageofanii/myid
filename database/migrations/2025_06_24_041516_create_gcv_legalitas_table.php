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
        Schema::create('gcv_legalitas', function (Blueprint $table) {
            $table->id();

            $table->string('siteplan')->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('id_rumah')->nullable();
            $table->enum('status_sertifikat', ['induk','pecahan'])->nullable();
            $table->string('nib')->nullable();
            $table->string('imb_pbg')->nullable();
            $table->string('nop')->nullable();
            $table->string('nop1')->nullable();

            $table->json('up_sertifikat')->nullable();
            $table->json('up_pbb')->nullable();
            $table->json('up_img')->nullable();
            $table->json('sertifikat_list')->nullable(); //noted: ini bukan database file.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_legalitas');
    }
};
