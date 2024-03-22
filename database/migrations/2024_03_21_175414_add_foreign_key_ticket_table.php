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
        Schema::table('ticket', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('no action')->onUpdate('no action');
 
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')
            ->on('area')->onDelete('no action')->onUpdate('no action');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')
            ->on('department')->onDelete('no action')->onUpdate('no action');
 
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')
            ->on('status')->onDelete('no action')->onUpdate('no action');
 
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')
            ->on('category')->onDelete('no action')->onUpdate('no action');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('area_id');
            $table->dropForeign('department_id');
            $table->dropForeign('status_id');
            $table->dropForeign('category_id');
        });
    }
};
