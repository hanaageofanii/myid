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
        Schema::create('gcv_datatanahs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index();
            $table->foreignIdFor(Team::class)->index();
            $table->string('no_bidang')->nullable();
            $table->string('nama_pemilik_asal')->nullable();
            $table->string('alas_hak')->nullable();
            $table->string('luas_surat')->nullable();
            $table->string('luas_ukur')->nullable();
            $table->string('nop')->nullable();
            $table->string('harga_jual')->nullable();
            $table->string('sph')->nullable();
            $table->string('notaris')->nullable();
            $table->string('catatan')->nullable();

            $table->json('up_sertifikat')->nullable();
            $table->json('up_nop')->nullable();
            $table->json('data_diri')->nullable();
            $table->json('up_sph')->nullable();
            $table->json('up_tambahan_lainnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_datatanahs');
    }
};