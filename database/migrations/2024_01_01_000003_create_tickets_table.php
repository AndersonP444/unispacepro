<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tickets / Reservas de butacas por función
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('funcion_id')->constrained('funciones')->onDelete('cascade');
            $table->foreignId('butaca_id')->constrained('butacas')->onDelete('cascade');
            $table->string('codigo_ticket', 60)->unique();        // Generado con CI + butaca + curso + horario
            $table->string('codigo_qr')->nullable();              // URL o datos del QR dinámico
            $table->string('google_calendar_evento_id')->nullable(); // ID retornado por Google Calendar API
            $table->enum('estado', ['activo', 'cancelado', 'completado'])->default('activo');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->timestamps();

            // Un usuario solo puede tener una butaca por función
            $table->unique(['funcion_id', 'butaca_id']);
            $table->unique(['usuario_id', 'funcion_id']);
        });

        // Cola de trabajos del sistema
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('cola', 255)->default('default');
            $table->longText('carga');
            $table->unsignedTinyInteger('intentos')->default(0);
            $table->unsignedInteger('reservado_en')->nullable();
            $table->unsignedInteger('disponible_en');
            $table->unsignedInteger('creado_en');
            $table->index(['cola', 'reservado_en', 'disponible_en']);
        });

        Schema::create('trabajos_fallidos', function (Blueprint $table) {
            $table->id();
            $table->text('conexion');
            $table->text('cola');
            $table->longText('carga');
            $table->longText('excepcion');
            $table->timestamp('fallado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajos_fallidos');
        Schema::dropIfExists('trabajos');
        Schema::dropIfExists('tickets');
    }
};
