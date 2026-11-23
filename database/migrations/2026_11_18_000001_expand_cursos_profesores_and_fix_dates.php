<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Funcion;
use App\Models\Salon;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Limpiar foto_perfil para usar avatares limpios basados en iniciales
        DB::table('usuarios')->update(['foto_perfil' => null]);

        // 2. Actualizar fechas de inicio de todos los cursos y talleres existentes al 30 de noviembre de 2026
        DB::table('cursos')->where('tipo', 'curso')->update([
            'fecha_inicio'    => '2026-11-30',
            'fecha_fin'       => '2027-03-22',
            'semanas_totales' => 16,
        ]);

        DB::table('cursos')->where('tipo', 'taller')->update([
            'fecha_inicio'    => '2026-11-30',
            'fecha_fin'       => '2027-01-11',
            'semanas_totales' => 6,
        ]);

        // Actualizar fechas y precios de los eventos del Auditorio (diciembre 2026)
        DB::table('cursos')->where('codigo', 'EVE-001')->update([
            'fecha_inicio' => '2026-12-04',
            'fecha_fin'    => '2026-12-04',
            'precio'       => 15.00,
        ]);
        DB::table('cursos')->where('codigo', 'EVE-002')->update([
            'fecha_inicio' => '2026-12-03',
            'fecha_fin'    => '2026-12-03',
            'precio'       => 10.00,
        ]);
        DB::table('cursos')->where('codigo', 'EVE-003')->update([
            'fecha_inicio' => '2026-12-02',
            'fecha_fin'    => '2026-12-02',
            'precio'       => 8.00,
        ]);

        // 3. Reubicar talleres existentes para cubrir A-11, A-12 y M-15 y asignar horarios sin choques (07:00 a 19:00)
        $salonesPorCodigo = Salon::pluck('id', 'codigo');

        if (isset($salonesPorCodigo['A-11'])) {
            DB::table('cursos')->where('codigo', 'TAL-002')->update(['salon_id' => $salonesPorCodigo['A-11']]);
        }
        if (isset($salonesPorCodigo['A-12'])) {
            DB::table('cursos')->where('codigo', 'TAL-003')->update(['salon_id' => $salonesPorCodigo['A-12']]);
        }
        if (isset($salonesPorCodigo['M-15'])) {
            DB::table('cursos')->where('codigo', 'TAL-005')->update(['salon_id' => $salonesPorCodigo['M-15']]);
        }

        // Horarios escalonados sin choques para los cursos/talleres/eventos existentes
        $horariosExistentes = [
            'CUR-001' => ['dia_semana' => 'lunes',     'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00'],
            'TAL-001' => ['dia_semana' => 'lunes',     'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00'],
            'TAL-002' => ['dia_semana' => 'lunes',     'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00'],
            'TAL-003' => ['dia_semana' => 'lunes',     'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00'],
            'CUR-002' => ['dia_semana' => 'lunes',     'hora_inicio' => '15:00:00', 'hora_fin' => '17:00:00'],
            'TAL-004' => ['dia_semana' => 'lunes',     'hora_inicio' => '17:00:00', 'hora_fin' => '19:00:00'],
            'TAL-005' => ['dia_semana' => 'martes',    'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00'],
            'CUR-004' => ['dia_semana' => 'martes',    'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00'],
            'TAL-006' => ['dia_semana' => 'martes',    'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00'],
            'CUR-003' => ['dia_semana' => 'martes',    'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00'],
            'EVE-003' => ['dia_semana' => 'miercoles', 'hora_inicio' => '14:00:00', 'hora_fin' => '17:00:00'],
            'EVE-002' => ['dia_semana' => 'jueves',    'hora_inicio' => '10:00:00', 'hora_fin' => '13:00:00'],
            'EVE-001' => ['dia_semana' => 'viernes',   'hora_inicio' => '10:00:00', 'hora_fin' => '13:00:00'],
        ];

        foreach ($horariosExistentes as $cod => $h) {
            $cursoObj = Curso::where('codigo', $cod)->first();
            if ($cursoObj) {
                DB::table('funciones')->where('curso_id', $cursoObj->id)->update($h);
            }
        }

        // 4. Crear 8 nuevos profesores especialistas y 8 nuevos cursos/talleres para A-3 hasta A-10
        $hashProf = '$2y$12$d7hyJVfd2hiOw1Qyy82hJe9pQmHUsi/15IEDF9arcPRARmNEO1fTK'; // Prof1234!

        $nuevosDocentesYCursos = [
            [
                'profesor' => [
                    'nombre'    => 'Roberto',
                    'apellido'  => 'Vargas',
                    'cedula'    => '19100201',
                    'email'     => 'rvargas@unispace.edu.ve',
                    'telefono'  => '04141900201',
                    'biografia' => 'Abogado especialista en Derecho Marítimo Internacional, convenios de la OMI y legislación portuaria venezolana.',
                    'gustos'    => 'Derecho Marítimo, Legislación Portuaria, Convenios OMI, Comercio Exterior',
                ],
                'curso' => [
                    'codigo'          => 'CUR-005',
                    'nombre'          => 'Legislación y Derecho Marítimo',
                    'descripcion'     => 'Estudio integral del marco jurídico marítimo, convenios internacionales de navegación, contratos de fletamento y normativa portuaria.',
                    'foto'            => 'images/cursos/idiomas.jpg',
                    'tipo'            => 'curso',
                    'salon_codigo'    => 'A-3',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-03-22',
                    'semanas_totales' => 16,
                    'dia_semana'      => 'martes',
                    'hora_inicio'     => '15:00:00',
                    'hora_fin'        => '17:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Carmen',
                    'apellido'  => 'Navarro',
                    'cedula'    => '19100202',
                    'email'     => 'cnavarro@unispace.edu.ve',
                    'telefono'  => '04121900202',
                    'biografia' => 'Magíster en Comercio Internacional y Gestión Aduanera con experiencia en logística de importación, exportación y aranceles.',
                    'gustos'    => 'Comercio Internacional, Aduanas, Logística Global, Incoterms',
                ],
                'curso' => [
                    'codigo'          => 'CUR-006',
                    'nombre'          => 'Comercio Internacional y Aduanas',
                    'descripcion'     => 'Análisis de operaciones de comercio exterior, regímenes aduaneros, valoración arancelaria y logística de transporte multimodal.',
                    'foto'            => 'images/cursos/tecnologia_num.jpg',
                    'tipo'            => 'curso',
                    'salon_codigo'    => 'A-4',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-03-22',
                    'semanas_totales' => 16,
                    'dia_semana'      => 'martes',
                    'hora_inicio'     => '17:00:00',
                    'hora_fin'        => '19:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Fernando',
                    'apellido'  => 'Silva',
                    'cedula'    => '19100203',
                    'email'     => 'fsilva@unispace.edu.ve',
                    'telefono'  => '04161900203',
                    'biografia' => 'Oceanógrafo e investigador en meteorología marina, análisis de cartas sinópticas, corrientes oceánicas y climatología costera.',
                    'gustos'    => 'Meteorología Marina, Oceanografía, Climatología, Cartas Sinópticas',
                ],
                'curso' => [
                    'codigo'          => 'TAL-007',
                    'nombre'          => 'Meteorología y Oceanografía',
                    'descripcion'     => 'Taller práctico de interpretación meteorológica aplicada a la navegación, pronóstico de oleaje, mareas y fenómenos atmosféricos.',
                    'foto'            => 'images/cursos/fisica_taller.jpg',
                    'tipo'            => 'taller',
                    'salon_codigo'    => 'A-5',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-01-11',
                    'semanas_totales' => 6,
                    'dia_semana'      => 'miercoles',
                    'hora_inicio'     => '07:00:00',
                    'hora_fin'        => '09:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Ricardo',
                    'apellido'  => 'Morales',
                    'cedula'    => '19100204',
                    'email'     => 'rmorales@unispace.edu.ve',
                    'telefono'  => '04241900204',
                    'biografia' => 'Capitán de Altura e instructor de navegación costera, electrónica y astronómica con amplia trayectoria en buques mercantes.',
                    'gustos'    => 'Navegación Costera, Cartografía Náutica, Radar ARPA, Sextante',
                ],
                'curso' => [
                    'codigo'          => 'TAL-008',
                    'nombre'          => 'Navegación Costera y Astronómica',
                    'descripcion'     => 'Taller de trazado de derrotas en cartas náuticas, posicionamiento por marcaciones, cálculo de rumbos y fundamentos astronómicos.',
                    'foto'            => 'images/cursos/calculo.jpg',
                    'tipo'            => 'taller',
                    'salon_codigo'    => 'A-6',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-01-11',
                    'semanas_totales' => 6,
                    'dia_semana'      => 'miercoles',
                    'hora_inicio'     => '09:00:00',
                    'hora_fin'        => '11:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Valeria',
                    'apellido'  => 'Rojas',
                    'cedula'    => '19100205',
                    'email'     => 'vrojas@unispace.edu.ve',
                    'telefono'  => '04141900205',
                    'biografia' => 'Ingeniera Ambiental dedicada a la conservación de ecosistemas marino-costeros, convenio MARPOL y gestión de impacto ambiental.',
                    'gustos'    => 'Gestión Ambiental, Ecosistemas Costeros, Convenio MARPOL, Sostenibilidad',
                ],
                'curso' => [
                    'codigo'          => 'CUR-007',
                    'nombre'          => 'Gestión Ambiental y Ecosistemas Costeros',
                    'descripcion'     => 'Curso especializado en prevención de la contaminación marina, manejo sustentable de zonas costeras y auditorías ambientales.',
                    'foto'            => 'images/cursos/quimica_curso.jpg',
                    'tipo'            => 'curso',
                    'salon_codigo'    => 'A-7',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-03-22',
                    'semanas_totales' => 16,
                    'dia_semana'      => 'miercoles',
                    'hora_inicio'     => '11:00:00',
                    'hora_fin'        => '13:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Héctor',
                    'apellido'  => 'Castillo',
                    'cedula'    => '19100206',
                    'email'     => 'hcastillo@unispace.edu.ve',
                    'telefono'  => '04121900206',
                    'biografia' => 'Especialista en Administración Portuaria, gestión de terminales de contenedores y planificación de cadenas de suministro.',
                    'gustos'    => 'Administración Portuaria, Terminales Marítimas, Cadena de Suministro, Operaciones',
                ],
                'curso' => [
                    'codigo'          => 'CUR-008',
                    'nombre'          => 'Administración Portuaria y Logística',
                    'descripcion'     => 'Planificación operativa de terminales marítimas, estiba y desestiba de carga, indicadores de rendimiento portuario y seguridad.',
                    'foto'            => 'images/cursos/tecnologia_digital.jpg',
                    'tipo'            => 'curso',
                    'salon_codigo'    => 'A-8',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-03-22',
                    'semanas_totales' => 16,
                    'dia_semana'      => 'jueves',
                    'hora_inicio'     => '07:00:00',
                    'hora_fin'        => '09:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Miguel',
                    'apellido'  => 'Herrera',
                    'cedula'    => '19100207',
                    'email'     => 'mherrera@unispace.edu.ve',
                    'telefono'  => '04161900207',
                    'biografia' => 'Oficial de Seguridad Marítima e instructor certificado en convenio SOLAS, control de averías y salvamento en la mar.',
                    'gustos'    => 'Seguridad Marítima, Convenio SOLAS, Salvamento, Control de Averías',
                ],
                'curso' => [
                    'codigo'          => 'TAL-009',
                    'nombre'          => 'Seguridad Marítima y Salvamento',
                    'descripcion'     => 'Taller práctico sobre protocolos internacionales de seguridad de la vida humana en el mar, supervivencia y gestión de emergencias.',
                    'foto'            => 'images/cursos/termodinamica.jpg',
                    'tipo'            => 'taller',
                    'salon_codigo'    => 'A-9',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-01-11',
                    'semanas_totales' => 6,
                    'dia_semana'      => 'jueves',
                    'hora_inicio'     => '15:00:00',
                    'hora_fin'        => '17:00:00',
                ],
            ],
            [
                'profesor' => [
                    'nombre'    => 'Gabriela',
                    'apellido'  => 'Medina',
                    'cedula'    => '19100208',
                    'email'     => 'gmedina@unispace.edu.ve',
                    'telefono'  => '04241900208',
                    'biografia' => 'Licenciada en Turismo y Hospitalidad con especialización en gestión de cruceros, turismo costero sustentable y protocolo.',
                    'gustos'    => 'Turismo Sostenible, Gestión de Cruceros, Patrimonio Costero, Hospitalidad',
                ],
                'curso' => [
                    'codigo'          => 'TAL-010',
                    'nombre'          => 'Turismo Sostenible y Gestión Hotelera',
                    'descripcion'     => 'Taller enfocado en planificación de destinos turísticos costeros, industria de cruceros, calidad de servicio y sostenibilidad.',
                    'foto'            => 'images/cursos/idiomas.jpg',
                    'tipo'            => 'taller',
                    'salon_codigo'    => 'A-10',
                    'fecha_inicio'    => '2026-11-30',
                    'fecha_fin'       => '2027-01-11',
                    'semanas_totales' => 6,
                    'dia_semana'      => 'viernes',
                    'hora_inicio'     => '07:00:00',
                    'hora_fin'        => '09:00:00',
                ],
            ],
        ];

        foreach ($nuevosDocentesYCursos as $item) {
            $pData = $item['profesor'];
            $profesor = Usuario::updateOrCreate(
                ['email' => $pData['email']],
                [
                    'rol_id'    => 4,
                    'nombre'    => $pData['nombre'],
                    'apellido'  => $pData['apellido'],
                    'cedula'    => $pData['cedula'],
                    'telefono'  => $pData['telefono'],
                    'biografia' => $pData['biografia'],
                    'gustos'    => $pData['gustos'],
                    'password'  => $hashProf,
                    'activo'    => true,
                ]
            );

            $cData = $item['curso'];
            $salonId = $salonesPorCodigo[$cData['salon_codigo']] ?? 6;
            $salonCapacidad = Salon::where('id', $salonId)->value('capacidad') ?? 20;

            $curso = Curso::updateOrCreate(
                ['codigo' => $cData['codigo']],
                [
                    'profesor_id'     => $profesor->id,
                    'salon_id'        => $salonId,
                    'nombre'          => $cData['nombre'],
                    'descripcion'     => $cData['descripcion'],
                    'foto'            => $cData['foto'],
                    'tipo'            => $cData['tipo'],
                    'fecha_inicio'    => $cData['fecha_inicio'],
                    'fecha_fin'       => $cData['fecha_fin'],
                    'semanas_totales' => $cData['semanas_totales'],
                    'precio'          => 0.00,
                    'activo'          => true,
                ]
            );

            Funcion::updateOrCreate(
                ['curso_id' => $curso->id],
                [
                    'dia_semana'  => $cData['dia_semana'],
                    'hora_inicio' => $cData['hora_inicio'],
                    'hora_fin'    => $cData['hora_fin'],
                    'cupo_maximo' => $salonCapacidad,
                ]
            );
        }
    }

    public function down(): void
    {
        //
    }
};

