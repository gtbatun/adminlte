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
        Schema::create('mantto', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('device_id');
            $table->text('coment');
            $table->bigInteger('user_id');
            $table->bigInteger('usermantto_id');
            $table->bigInteger('statusdevice_id');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantto');
    }
};
