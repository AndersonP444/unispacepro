<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use App\Models\Salon;
use App\Models\Butaca;
use App\Models\Curso;
use App\Models\Funcion;
use App\Models\Usuario;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expandir enum tipo en cursos y agregar columna precio
        DB::statement("ALTER TABLE cursos MODIFY COLUMN tipo ENUM('curso','taller','clase','evento') NOT NULL DEFAULT 'clase'");
        if (!Schema::hasColumn('cursos', 'precio')) {
            DB::statement("ALTER TABLE cursos ADD COLUMN precio DECIMAL(8,2) NOT NULL DEFAULT 0.00 AFTER semanas_totales");
        }

        // 2. Expandir enum estado en tickets y agregar columnas de pago móvil
        DB::statement("ALTER TABLE tickets MODIFY COLUMN estado ENUM('activo','pendiente','cancelado','completado') NOT NULL DEFAULT 'activo'");
        if (!Schema::hasColumn('tickets', 'referencia_pago')) {
            DB::statement("ALTER TABLE tickets ADD COLUMN referencia_pago VARCHAR(100) NULL AFTER estado");
            DB::statement("ALTER TABLE tickets ADD COLUMN telefono_pago VARCHAR(30) NULL AFTER referencia_pago");
            DB::statement("ALTER TABLE tickets ADD COLUMN banco_pago VARCHAR(80) NULL AFTER telefono_pago");
            DB::statement("ALTER TABLE tickets ADD COLUMN monto_pagado DECIMAL(8,2) NULL AFTER banco_pago");
        }

        // 3. Descargar imágenes para Auditorio y Eventos
        @mkdir(public_path('images/salones'), 0777, true);
        @mkdir(public_path('images/cursos'), 0777, true);

        $imgs = [
            'images/salones/auditorio.jpg'        => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=900&q=80',
            'images/cursos/graduacion.jpg'        => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=900&q=80',
            'images/cursos/congreso_maritimo.jpg' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80',
            'images/cursos/simposio_tech.jpg'     => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=900&q=80',
        ];

        foreach ($imgs as $path => $url) {
            try {
                $resp = Http::timeout(8)->withOptions(['verify' => false])->get($url);
                if ($resp->successful()) {
                    file_put_contents(public_path($path), $resp->body());
                } else {
                    @copy(public_path('images/carousel/edificio_nautica.jpg'), public_path($path));
                }
            } catch (\Exception $e) {
                @copy(public_path('images/carousel/edificio_nautica.jpg'), public_path($path));
            }
        }

        // 4. Crear el Auditorio Principal UMC (8 filas x 12 columnas = 96 butacas)
        $edificioId = DB::table('edificios')->value('id') ?? 1;
        $auditorio = Salon::updateOrCreate(
            ['codigo' => 'AUD-01'],
            [
                'edificio_id' => $edificioId,
                'nombre'      => 'Auditorio Principal UMC',
                'filas'       => 8,
                'columnas'    => 12,
                'capacidad'   => 96,
                'foto'        => 'images/salones/auditorio.jpg',
                'equipos'     => 'Escenario Principal, Sonido Profesional, Pantalla Gigante LED, Iluminación Escénica, Climatización Central',
                'estado'      => 'disponible',
            ]
        );

        if ($auditorio->butacas()->count() === 0) {
            $letras = range('A', 'Z');
            for ($f = 1; $f <= $auditorio->filas; $f++) {
                for ($c = 1; $c <= $auditorio->columnas; $c++) {
                    Butaca::create([
                        'salon_id' => $auditorio->id,
                        'fila'     => $f,
                        'columna'  => $c,
                        'etiqueta' => $letras[$f - 1] . $c,
                        'activa'   => true,
                    ]);
                }
            }
        }

        // 5. Crear Eventos Especiales en el Auditorio
        $profId = Usuario::where('rol_id', 4)->value('id') ?? 3;
        $eventos = [
            [
                'codigo'          => 'EVE-001',
                'nombre'          => 'Acto Solemne de Graduación Promoción UMC',
                'descripcion'     => 'Ceremonia oficial de conferimiento de títulos universitarios y medallas de grado en el Auditorio Principal.',
                'foto'            => 'images/cursos/graduacion.jpg',
                'tipo'            => 'evento',
                'profesor_id'     => $profId,
                'salon_id'        => $auditorio->id,
                'fecha_inicio'    => '2026-11-20',
                'fecha_fin'       => '2026-11-20',
                'semanas_totales' => 1,
                'precio'          => 15.00,
                'activo'          => true,
                'horario'         => ['dia_semana' => 'viernes', 'hora_inicio' => '10:00', 'hora_fin' => '13:00', 'cupo_maximo' => 96],
            ],
            [
                'codigo'          => 'EVE-002',
                'nombre'          => 'Congreso Internacional Marítimo y Portuario',
                'descripcion'     => 'Ponencias magistrales sobre logística naviera, derecho marítimo y transporte acuático internacional.',
                'foto'            => 'images/cursos/congreso_maritimo.jpg',
                'tipo'            => 'evento',
                'profesor_id'     => $profId,
                'salon_id'        => $auditorio->id,
                'fecha_inicio'    => '2026-11-26',
                'fecha_fin'       => '2026-11-26',
                'semanas_totales' => 1,
                'precio'          => 10.00,
                'activo'          => true,
                'horario'         => ['dia_semana' => 'jueves', 'hora_inicio' => '09:00', 'hora_fin' => '12:00', 'cupo_maximo' => 96],
            ],
            [
                'codigo'          => 'EVE-003',
                'nombre'          => 'Simposio de Innovación Tecnológica y Energías',
                'descripcion'     => 'Encuentro especial de proyectos de ingeniería, automatización, inteligencia artificial y energías limpias.',
                'foto'            => 'images/cursos/simposio_tech.jpg',
                'tipo'            => 'evento',
                'profesor_id'     => $profId,
                'salon_id'        => $auditorio->id,
                'fecha_inicio'    => '2026-12-02',
                'fecha_fin'       => '2026-12-02',
                'semanas_totales' => 1,
                'precio'          => 8.00,
                'activo'          => true,
                'horario'         => ['dia_semana' => 'miercoles', 'hora_inicio' => '14:00', 'hora_fin' => '17:00', 'cupo_maximo' => 96],
            ],
        ];

        foreach ($eventos as $ev) {
            $hor = $ev['horario'];
            unset($ev['horario']);
            $cursoEv = Curso::updateOrCreate(['codigo' => $ev['codigo']], $ev);
            if ($cursoEv->funciones()->count() === 0) {
                Funcion::create(array_merge(['curso_id' => $cursoEv->id], $hor));
            }
        }
    }

    public function down(): void
    {
        //
    }
};

