<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas_atencion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamiento_id')->constrained('tratamientos')->restrictOnDelete();
            $table->string('paciente', 120);
            $table->dateTime('fecha_atencion');
            $table->string('motivo', 255);
            $table->text('observaciones')->nullable();
            $table->string('estado', 20)->default('pendiente');
            $table->timestamps();

            $table->index(['fecha_atencion', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas_atencion');
    }
};
