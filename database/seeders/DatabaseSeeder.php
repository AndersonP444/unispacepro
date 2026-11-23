<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Carrera;
use App\Models\Usuario;
use App\Models\Edificio;
use App\Models\Salon;
use App\Models\Butaca;
use App\Models\Curso;
use App\Models\Funcion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. ROLES ─────────────────────────────────────────────
        $roles = [
            ['nombre' => 'admin',       'nombre_display' => 'Administrador'],
            ['nombre' => 'supervisor',  'nombre_display' => 'Supervisor'],
            ['nombre' => 'estudiante',  'nombre_display' => 'Estudiante'],
            ['nombre' => 'profesor',    'nombre_display' => 'Profesor'],
        ];
        foreach ($roles as $rol) {
            Rol::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }

        // ── 2. CARRERAS ───────────────────────────────────────────
        $carreras = [
            ['nombre' => 'Ingeniería Marítima',          'codigo' => 'IMA'],
            ['nombre' => 'Ingeniería en Informática',    'codigo' => 'IIN'],
            ['nombre' => 'Ingeniería Ambiental',         'codigo' => 'IAM'],
            ['nombre' => 'Licenciatura en Administración','codigo' => 'LAD'],
            ['nombre' => 'Licenciatura en Turismo',      'codigo' => 'LTU'],
            ['nombre' => 'TSU en Transporte Acuático',   'codigo' => 'TTA'],
        ];
        foreach ($carreras as $c) {
            Carrera::firstOrCreate(['codigo' => $c['codigo']], $c);
        }

        // ── 3. USUARIOS ───────────────────────────────────────────
        // Admin
        Usuario::firstOrCreate(['cedula' => '00000001'], [
            'rol_id'    => 1,
            'nombre'    => 'Admin',
            'apellido'  => 'Sistema',
            'email'     => 'admin@unispace.edu.ve',
            'password'  => Hash::make('Admin1234!'),
            'activo'    => true,
        ]);

        // Supervisor
        Usuario::firstOrCreate(['cedula' => '10000001'], [
            'rol_id'    => 2,
            'nombre'    => 'Carlos',
            'apellido'  => 'Mendoza',
            'email'     => 'supervisor@unispace.edu.ve',
            'password'  => Hash::make('Super1234!'),
            'activo'    => true,
        ]);

        // Profesores
        $profesores = [
            ['cedula' => '12345678', 'nombre' => 'María',   'apellido' => 'González', 'email' => 'mgonzalez@unispace.edu.ve'],
            ['cedula' => '23456789', 'nombre' => 'José',    'apellido' => 'Ramírez',  'email' => 'jramirez@unispace.edu.ve'],
            ['cedula' => '34567890', 'nombre' => 'Ana',     'apellido' => 'Torres',   'email' => 'atorres@unispace.edu.ve'],
        ];
        foreach ($profesores as $p) {
            Usuario::firstOrCreate(['cedula' => $p['cedula']], array_merge($p, [
                'rol_id'   => 4,
                'password' => Hash::make('Prof1234!'),
                'activo'   => true,
            ]));
        }

        // Estudiantes
        $estudiantes = [
            ['cedula' => '28100001', 'nombre' => 'Andrés',  'apellido' => 'Pérez',    'email' => 'aperez@gmail.com',    'carrera_id' => 1],
            ['cedula' => '28100002', 'nombre' => 'Luisa',   'apellido' => 'Martínez', 'email' => 'lmartinez@gmail.com', 'carrera_id' => 2],
            ['cedula' => '28100003', 'nombre' => 'Diego',   'apellido' => 'Flores',   'email' => 'dflores@gmail.com',   'carrera_id' => 1],
        ];
        foreach ($estudiantes as $e) {
            Usuario::firstOrCreate(['cedula' => $e['cedula']], array_merge($e, [
                'rol_id'   => 3,
                'password' => Hash::make('Est1234!'),
                'activo'   => true,
            ]));
        }

        // ── 4. EDIFICIOS ──────────────────────────────────────────
        $edificios = [
            ['nombre' => 'Bloque A – Ciencias',      'codigo' => 'BLQ-A', 'descripcion' => 'Laboratorios y aulas de ciencias exactas',      'mapa_x' => 25.5, 'mapa_y' => 30.2],
            ['nombre' => 'Bloque B – Humanidades',   'codigo' => 'BLQ-B', 'descripcion' => 'Aulas de humanidades y ciencias sociales',        'mapa_x' => 55.0, 'mapa_y' => 30.2],
            ['nombre' => 'Bloque C – Tecnología',    'codigo' => 'BLQ-C', 'descripcion' => 'Laboratorios de computación y tecnología',        'mapa_x' => 25.5, 'mapa_y' => 65.0],
            ['nombre' => 'Bloque D – Administración','codigo' => 'BLQ-D', 'descripcion' => 'Aulas de administración y postgrado',             'mapa_x' => 55.0, 'mapa_y' => 65.0],
        ];
        foreach ($edificios as $e) {
            Edificio::firstOrCreate(['codigo' => $e['codigo']], $e);
        }

        // ── 5. SALONES Y BUTACAS ──────────────────────────────────
        $salones = [
            ['edificio_id' => 1, 'nombre' => 'Aula A-101', 'codigo' => 'A-101', 'filas' => 6, 'columnas' => 8,  'estado' => 'disponible',    'equipos' => 'Proyector, Pizarrón inteligente, 48 computadoras'],
            ['edificio_id' => 1, 'nombre' => 'Aula A-102', 'codigo' => 'A-102', 'filas' => 5, 'columnas' => 6,  'estado' => 'en_uso',        'equipos' => 'Proyector, Aire acondicionado'],
            ['edificio_id' => 2, 'nombre' => 'Aula B-201', 'codigo' => 'B-201', 'filas' => 8, 'columnas' => 10, 'estado' => 'disponible',    'equipos' => 'Proyector, 80 sillas universitarias'],
            ['edificio_id' => 3, 'nombre' => 'Lab C-301',  'codigo' => 'C-301', 'filas' => 4, 'columnas' => 7,  'estado' => 'mantenimiento', 'equipos' => '28 computadoras, Pizarrón, Router'],
            ['edificio_id' => 4, 'nombre' => 'Aula D-401', 'codigo' => 'D-401', 'filas' => 6, 'columnas' => 9,  'estado' => 'disponible',    'equipos' => 'Proyector, Sistema de audio'],
        ];

        foreach ($salones as $s) {
            $capacidad = $s['filas'] * $s['columnas'];
            $salon = Salon::firstOrCreate(['codigo' => $s['codigo']], array_merge($s, ['capacidad' => $capacidad]));

            // Generar butacas si aún no existen
            if ($salon->butacas()->count() === 0) {
                $letras = range('A', 'Z');
                for ($f = 1; $f <= $salon->filas; $f++) {
                    for ($c = 1; $c <= $salon->columnas; $c++) {
                        Butaca::create([
                            'salon_id' => $salon->id,
                            'fila'     => $f,
                            'columna'  => $c,
                            'etiqueta' => $letras[$f - 1] . $c,
                            'activa'   => true,
                        ]);
                    }
                }
            }
        }

        // ── 6. CURSOS Y FUNCIONES ─────────────────────────────────
        $profesorIds = Usuario::where('rol_id', 4)->pluck('id')->toArray();
        $salonIds    = Salon::where('estado', 'disponible')->pluck('id')->toArray();

        $cursos = [
            [
                'profesor_id'     => $profesorIds[0],
                'salon_id'        => $salonIds[0],
                'nombre'          => 'Taller de Robótica',
                'codigo'          => 'TAL-ROB-001',
                'descripcion'     => 'Introducción a la robótica con Arduino y sensores.',
                'tipo'            => 'taller',
                'fecha_inicio'    => '2025-07-07',
                'fecha_fin'       => '2025-08-18',
                'semanas_totales' => 6,
                'activo'          => true,
                'funciones'       => [
                    ['dia_semana' => 'lunes',   'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'cupo_maximo' => 20],
                    ['dia_semana' => 'miercoles','hora_inicio'=> '09:00', 'hora_fin' => '11:00', 'cupo_maximo' => 20],
                ],
            ],
            [
                'profesor_id'     => $profesorIds[1],
                'salon_id'        => $salonIds[1],
                'nombre'          => 'Curso de Programación Web',
                'codigo'          => 'CUR-WEB-001',
                'descripcion'     => 'Desarrollo web con HTML, CSS y JavaScript desde cero.',
                'tipo'            => 'curso',
                'fecha_inicio'    => '2025-07-14',
                'fecha_fin'       => '2025-09-01',
                'semanas_totales' => 8,
                'activo'          => true,
                'funciones'       => [
                    ['dia_semana' => 'martes',  'hora_inicio' => '14:00', 'hora_fin' => '16:00', 'cupo_maximo' => 25],
                    ['dia_semana' => 'jueves',  'hora_inicio' => '14:00', 'hora_fin' => '16:00', 'cupo_maximo' => 25],
                ],
            ],
            [
                'profesor_id'     => $profesorIds[2],
                'salon_id'        => $salonIds[2] ?? $salonIds[0],
                'nombre'          => 'Clase de Cálculo I',
                'codigo'          => 'CLA-CAL-001',
                'descripcion'     => 'Fundamentos de cálculo diferencial e integral.',
                'tipo'            => 'clase',
                'fecha_inicio'    => '2025-07-07',
                'fecha_fin'       => '2025-11-28',
                'semanas_totales' => 20,
                'activo'          => true,
                'funciones'       => [
                    ['dia_semana' => 'lunes',   'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'cupo_maximo' => 40],
                    ['dia_semana' => 'miercoles','hora_inicio'=> '07:00', 'hora_fin' => '09:00', 'cupo_maximo' => 40],
                    ['dia_semana' => 'viernes', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'cupo_maximo' => 40],
                ],
            ],
        ];

        foreach ($cursos as $datos) {
            $funciones = $datos['funciones'];
            unset($datos['funciones']);

            $curso = Curso::firstOrCreate(['codigo' => $datos['codigo']], $datos);

            if ($curso->funciones()->count() === 0) {
                foreach ($funciones as $f) {
                    Funcion::create(array_merge(['curso_id' => $curso->id], $f));
                }
            }
        }

        $this->command->info('✅ Base de datos sembrada con datos de prueba en español.');
    }
}
