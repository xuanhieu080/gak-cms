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
        Schema::dropIfExists('seo_contents');
        Schema::create('seo_contents', function (Blueprint $table) {
            $table->id();
            $table->string('link', 355)->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
