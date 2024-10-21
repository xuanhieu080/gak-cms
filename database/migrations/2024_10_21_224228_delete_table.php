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
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('variants');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
