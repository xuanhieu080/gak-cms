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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->string('name')->default(0)->nullable();
            $table->string('params')->default(0)->nullable();
            $table->integer('qty')->default(0)->nullable();
            $table->double('price')->default(0)->nullable();
            $table->json('options')->nullable();
            $table->json('option_all')->nullable();
            $table->json('option_group')->nullable();
            $table->text('option_name')->nullable();
            $table->double('price_discount')->default(0)->nullable();
            $table->integer('discount')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
