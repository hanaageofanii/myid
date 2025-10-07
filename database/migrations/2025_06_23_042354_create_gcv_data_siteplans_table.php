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
        Schema::create('gcv_data_siteplans', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan')->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->integer('luas')->nullable();
            $table->string('type')->nullable();
            $table->boolean('terbangun')->default(false)->nullable();
            $table->string('keterangan')->nullable();
            $table->foreignIdFor(Team::class)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcv_data_siteplans');
    }
};
