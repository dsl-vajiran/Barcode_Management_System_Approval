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
        Schema::create('ybimmst', function (Blueprint $table) {
            $table->string('itmcode', 25)->primary();
            $table->string('itmnme', 100);
            $table->string('itmmod', 100)->nullable();
            $table->string('itmamp', 25)->nullable();
            $table->integer('f_war')->nullable();
            $table->integer('pa_war')->nullable();
            $table->string('remark', 250)->nullable();
            $table->string('prphase', 250)->nullable();
            $table->string('brand', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ybimmst');
    }
};
