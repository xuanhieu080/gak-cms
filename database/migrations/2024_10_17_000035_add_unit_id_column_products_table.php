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
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->renameColumn('price_discount', 'price_sale')->change();
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('sku')->nullable();
        });
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('tax_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'unit_id']);
            $table->renameColumn('price_sale', 'price_discount')->change();
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['sku']);
        });
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['tax_code']);
        });
    }
};
