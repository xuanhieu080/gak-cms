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
            $table->dropColumn(['qty','qty_sold']);
        });


        Schema::table('variant_details', function (Blueprint $table) {
            $table->string('attribute_id')->nullable()->change();
            $table->renameColumn('attribute_id','attribute_name');
        });


        Schema::table('product_attributes', function (Blueprint $table) {
            $table->json('attribute')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('qty')->default(100);
            $table->integer('qty_sold')->default(0);
        });

        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn('attribute');
        });

        Schema::table('variant_details', function (Blueprint $table) {
            $table->renameColumn('attribute_name','attribute_id');
        });
    }
};
