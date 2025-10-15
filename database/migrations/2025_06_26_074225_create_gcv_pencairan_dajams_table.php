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
        Schema::create('gcv_pencairan_dajams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index();
            $table->foreignIdFor(Team::class)->index();
            $table->string('siteplan')->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('bank')->nullable();
            $table->string('no_debitur')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->enum('nama_dajam',['sertifikat','imb','listrik','jkk','bestek','pph','bphtb'])->nullable();
            $table->string('nilai_dajam')->nullable();
            $table->date('tanggal_pencairan')->nullable();
            $table->string('nilai_pencairan')->nullable();
            $table->string('selisih_dajam')->nullable();
            $table->json('up_rekening_koran')->nullable();
            $table->json('up_lainnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_pencairan_dajams');
    }
};