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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan');
            $table->string('type');
            $table->boolean('terbangun')->default(false);
            $table->enum('status', ['akad'])->nullable();

            $table->string('kode1')->nullable();
            $table->integer('luas1')->nullable();
            $table->string('kode2')->nullable();
            $table->integer('luas2')->nullable();
            $table->string('kode3')->nullable();
            $table->integer('luas3')->nullable();
            $table->string('kode4')->nullable();
            $table->integer('luas4')->nullable();

            $table->string('tanda_terima_sertifikat')->nullable();
            $table->string('nop_pbb_pecahan')->nullable();
            $table->string('tanda_terima_nop')->nullable();
            $table->string('imb_pbg')->nullable();
            $table->string('tanda_terima_imb_pbg')->nullable();
            $table->text('tanda_terima_tambahan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};