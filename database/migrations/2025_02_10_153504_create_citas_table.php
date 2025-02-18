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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('title'); // Título de la cita o evento
            $table->text('description')->nullable(); // Descripción opcional
            $table->dateTime('start'); // Fecha y hora de inicio
            $table->dateTime('end')->nullable(); // Fecha y hora de fin opcional
            $table->string('color')->default('#007bff'); // Color del evento
            $table->unsignedBigInteger('user_id')->nullable(); // Usuario que creó la cita
            $table->timestamps();

            // Clave foránea para relacionar con la tabla users (si aplica)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
