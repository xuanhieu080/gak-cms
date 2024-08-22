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
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('description')->nullable();
            $table->timestamps();
        });
        $tableNames = config('permission.table_names');
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('title')->after('name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('group_id')->nullable();
        });
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_groups');
        $tableNames = config('permission.table_names');
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn(['description', 'group_id', 'title']);
        });
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }
};
