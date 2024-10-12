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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('attribute_group_id')->nullable();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unique(['attribute_id', 'attribute_group_id', 'product_id'], 'product_attribute_index')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
