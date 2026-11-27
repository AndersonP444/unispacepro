<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\Curso;

// Este controlador se encarga de manejar las pantallas que cualquiera puede ver sin necesidad de loguearse en el sistema,
// como la página de inicio, la lista de salones/espacios físicos, los cursos activos y el listado de profesores.
class PublicController extends Controller
{
    // Carga la página de bienvenida principal.
    public function index()
    {
        $stats = [
            'salones'    => Salon::count(),
            'cursos'     => Curso::where('activo', true)->count(),
            'profesores' => \App\Models\Usuario::where('rol_id', 4)->count(),
            'butacas'    => \App\Models\Butaca::where('activa', true)->count(),
        ];
        $cursosDestacados = Curso::with(['profesor', 'salon', 'funciones'])
            ->where('activo', true)
            ->take(3)
            ->get();

        return view('welcome', compact('stats', 'cursosDestacados'));
    }

    // Muestra todos los salones y laboratorios registrados.
    // Usamos eager loading para cargar de una vez la información del edificio asociado a cada salón y optimizar las consultas a la base de datos.
    public function espacios()
    {
        $salones = Salon::with(['edificio', 'cursos' => function ($query) {
            $query->where('activo', true)->with('profesor');
        }])->get();
        return view('public.espacios', compact('salones'));
    }

    // Lista las materias, talleres o cursos que están activos en la universidad.
    // Traemos de forma eficiente los datos del docente, el salón y las funciones/horarios programados.
    public function cursos()
    {
        $cursos = Curso::with(['profesor.cursos', 'funciones', 'salon'])
            ->where('activo', true)
            ->where('tipo', '!=', 'evento')
            ->get();
        return view('public.cursos', compact('cursos'));
    }

    // Muestra el apartado especial del Auditorio para Eventos, Graduaciones y Congresos (de pago).
    public function auditorio()
    {
        $auditorio = Salon::with('edificio')->where('codigo', 'AUD-01')->first();
        $eventos = Curso::with(['profesor', 'funciones.tickets', 'salon'])
            ->where('tipo', 'evento')
            ->where('activo', true)
            ->get();
        return view('public.auditorio', compact('auditorio', 'eventos'));
    }

    // Filtra y muestra a las personas registradas que tienen el rol de Profesor (rol_id igual a 4).
    public function profesores()
    {
        $profesores = \App\Models\Usuario::with(['cursos' => function ($query) {
            $query->where('activo', true)->with(['salon', 'funciones']);
        }])->where('rol_id', 4)->get();
        return view('public.profesores', compact('profesores'));
    }
}
