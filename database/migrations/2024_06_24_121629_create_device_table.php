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
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_equipo_id');
            $table->text('name');
            $table->bigInteger('marca_id')->nullable();
            $table->text('serie')->nullable();
            $table->bigInteger('almacenamiento_id')->nullable();
            $table->bigInteger('procesador_id')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('statusdevice_id')->nullable();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('department')->onDelete('no action')->onUpdate('no action');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');

            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('no action')->onUpdate('no action');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device');
    }
};
