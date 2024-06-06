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
        Schema::table('department', function (Blueprint $table) {
            $table->BigInteger('multi')->nullable();
            $table->BigInteger('enableforticket')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department', function (Blueprint $table) {
            $table->BigInteger('multi');
            $table->BigInteger('enableforticket');
        });
    }
};
