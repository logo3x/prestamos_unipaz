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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sede_id')->constrained('sedes');
            $table->string('aula');
            $table->foreignId('programa_id')->constrained('programas');
            $table->string('asignatura');
            $table->string('actividad')->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->string('evidencia_path')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado')->default('confirmada');
            $table->timestamps();

            $table->index(['equipo_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
