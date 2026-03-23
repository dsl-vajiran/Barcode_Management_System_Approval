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
        Schema::create('ybissue', function (Blueprint $table) {
            $table->string('ibarcode', 100)->primary();
            $table->string('invno', 25);
            $table->string('itmnme', 100);
            $table->string('itmmod', 100);
            $table->string('itmamp', 25);
            $table->integer('f_war')->nullable();
            $table->integer('pa_war')->nullable();
            $table->string('remark', 250)->nullable();
            $table->string('prphase', 250)->nullable();
            $table->string('brand', 150);
            $table->dateTime('isudtme')->useCurrent();
            $table->string('iremark', 250)->nullable();
            $table->integer('ichsale')->default(0);
            $table->dateTime('saledtme')->nullable();
            $table->integer('ichapr')->default(0);
            $table->dateTime('iaprdte')->nullable();
            $table->string('fncusnm', 250)->nullable();
            $table->string('fncustp', 250)->nullable();
            $table->string('location', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ybissue');
    }
};
