<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Edificios / bloques del campus universitario
        Schema::create('edificios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique();       // Ej: "BLOQUE-A"
            $table->text('descripcion')->nullable();
            $table->decimal('mapa_x', 8, 4)->nullable();  // Coordenada X en el plano general
            $table->decimal('mapa_y', 8, 4)->nullable();  // Coordenada Y en el plano general
            $table->timestamps();
        });

        // Salones dentro de cada edificio
        Schema::create('salones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edificio_id')->constrained('edificios')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique();        // Ej: "A-101"
            $table->integer('filas');                      // Número de filas de butacas
            $table->integer('columnas');                   // Número de columnas de butacas
            $table->integer('capacidad');                  // Total: filas * columnas
            $table->string('foto')->nullable();            // Foto del salón
            $table->text('equipos')->nullable();           // Inventario de equipos
            $table->enum('estado', ['disponible', 'en_uso', 'mantenimiento'])->default('disponible');
            $table->timestamps();
        });

        // Butacas individuales de cada salón (estilo cine)
        Schema::create('butacas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id')->constrained('salones')->onDelete('cascade');
            $table->integer('fila');
            $table->integer('columna');
            $table->string('etiqueta', 10);               // Ej: "A1", "B3"
            $table->boolean('activa')->default(true);     // Para inhabilitar butacas dañadas
            $table->timestamps();

            $table->unique(['salon_id', 'fila', 'columna']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('butacas');
        Schema::dropIfExists('salones');
        Schema::dropIfExists('edificios');
    }
};
