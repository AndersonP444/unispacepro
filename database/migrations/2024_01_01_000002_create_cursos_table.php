<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cursos / Talleres / Clases disponibles en la institución
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('salon_id')->constrained('salones')->onDelete('cascade');
            $table->string('nombre', 150);
            $table->string('codigo', 30)->unique();         // Ej: "TAL-ROB-001"
            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable();             // Foto del curso/taller
            $table->enum('tipo', ['curso', 'taller', 'clase'])->default('clase');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('semanas_totales');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Funciones = sesiones recurrentes semanales del curso (horario + día)
        Schema::create('funciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cupo_maximo');                // Cupo total disponible por función
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funciones');
        Schema::dropIfExists('cursos');
    }
};
