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
        Schema::create('gcv_master_dajams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index();
            $table->foreignIdFor(Team::class)->index();
            $table->enum('kavling', ['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('siteplan')->nullable( );
            $table->string('nop')->nullable( );
            $table->string('nama_konsumen')->nullable( );
            $table->string('nik')->nullable( );
            $table->string('npwp')->nullable( );
            $table->string('alamat')->nullable( );
            $table->string('suket_validasi')->nullable( );
            $table->string('no_sspd_bphtb')->nullable( );
            $table->date('tanggal_sspd_bphtb')->nullable( );
            $table->string('no_validasi_sspd')->nullable( );
            $table->date('tanggal_validasi_sspd')->nullable( );
            $table->string('notaris')->nullable( );
            $table->string('no_ajb')->nullable( );
            $table->date('tanggal_ajb')->nullable( );
            $table->json('up_bast')->nullable( );
            $table->json('up_validasi')->nullable( );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_master_dajams');
    }
};