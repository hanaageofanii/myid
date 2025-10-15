<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Team;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gcv_kaskecils', function (Blueprint $table) {
            $table->id();
                        $table->foreignIdFor(User::class)->index();
            $table->foreignIdFor(Team::class)->index();
            $table->enum('nama_perusahaan', ['langgeng_pertiwi_development','agung_purnama_bakti','purnama_karya_bersama'])->nullable();
            $table->date('tanggal')->nullable();
            $table->string('deskripsi')->nullable();
            $table->enum('tipe',['debit','kredit'])->nullable();
            $table->string('jumlah_uang')->nullable();
            $table->string('saldo')->nullable();
            $table->string('catatan')->nullable();
            $table->json('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_kaskecils');
    }
};