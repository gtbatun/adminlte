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
        Schema::table('area', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->after('id')->nullable();
            $table->foreign('department_id')->references('id')
            ->on('department')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area', function (Blueprint $table) {
            $table->dropForeign('department_id');
        });
    }
};
