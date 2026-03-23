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
        Schema::create('ybgrn', function (Blueprint $table) {
            $table->string('gbarcode', 100)->primary();
            $table->string('gitmcode', 25);
            $table->date('gdte');
            $table->string('grnno', 100);
            $table->string('gcrtusr', 250);
            $table->dateTime('gcrtdtme')->useCurrent();
            $table->string('gremark', 250)->nullable();
            $table->integer('gchprt')->default(0);
            $table->integer('gchact')->default(1);
            $table->string('whscode', 10)->nullable();
            $table->integer('chwhsprt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ybgrn');
    }
};
