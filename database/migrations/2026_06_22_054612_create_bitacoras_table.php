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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('tipo_caso');
            $table->string('proceso');
            $table->text('descripcion');
            $table->enum('prioridad', ['BAJA', 'MEDIA', 'ALTA', 'CRITICA'])->default('MEDIA');
            $table->text('solucion')->nullable();

            $table->dateTime('fecha_registro');
            $table->integer('tiempo_resolucion')->nullable();

            $table->enum('estado', ['PENDIENTE', 'EN_PROCESO', 'RESUELTO', 'CERRADO'])->default('PENDIENTE');

            $table->string('area')->nullable();
            $table->string('personal')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
