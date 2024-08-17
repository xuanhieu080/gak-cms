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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('params')->nullable();
        });
        Schema::table('variants', function (Blueprint $table) {
            $table->boolean('is_hot')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['params']);
        });
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn(['is_hot']);
        });
    }
};
