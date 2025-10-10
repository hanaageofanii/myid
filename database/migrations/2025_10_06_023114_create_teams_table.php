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
        // tabel teams
        Schema::create('teams', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});


Schema::create('team_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('team_id');
    $table->unsignedBigInteger('user_id');
    $table->timestamps();

    $table->unique(['team_id', 'user_id']);

    // beri nama foreign key unik supaya MySQL tidak bikin default '1'
    $table->foreign('team_id', 'team_user_team_id_fk')
          ->references('id')->on('teams')
          ->onDelete('cascade');

    $table->foreign('user_id', 'team_user_user_id_fk')
          ->references('id')->on('users')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user'); // harus drop pivot dulu
        Schema::dropIfExists('teams');
    }
};
