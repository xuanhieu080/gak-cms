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
        Schema::create('material_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->double('qty')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['material_id', 'warehouse_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('product_warehouses', function (Blueprint $table) {
            $table->unique(['product_id','warehouse_id', 'variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_warehouses');

        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedBigInteger('warehouse_id')->nullable();
        });

        Schema::table('product_warehouses', function (Blueprint $table) {
            $table->dropUnique(['product_id','warehouse_id', 'variant_id']);
        });
    }
};
