<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Ticket;
use App\Models\Curso;

// Este controlador maneja la pantalla principal de usuario una vez que inicia sesión.
// Tiene lógica para dos tipos de perfiles:
// 1. Si es un Profesor: Muestra sus cursos asignados junto a los estudiantes que han reservado asientos.
// 2. Si es un Estudiante: Muestra sus tickets de reserva activos, su historial de reservas previas y un panel del clima.
class DashboardController extends Controller
{
    // Carga la información principal del panel según el rol del usuario autenticado.
    public function index()
    {
        $usuario = Auth::user();

        // 0. Si es Administrador, cargamos el Panel de Control en Tiempo Real (Cursos, Espacios, Profesores y Solicitudes de Pago del Auditorio).
        if ($usuario->esAdmin()) {
            $solicitudesPendientes = Ticket::with(['usuario', 'funcion.curso.salon', 'butaca'])
                ->where('estado', 'pendiente')
                ->orderBy('created_at', 'desc')
                ->get();

            $ticketsAuditorioActivos = Ticket::with(['usuario', 'funcion.curso.salon', 'butaca'])
                ->where('estado', 'activo')
                ->whereHas('funcion.curso', function ($q) {
                    $q->where('tipo', 'evento');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $cursos = Curso::with(['profesor', 'salon', 'funciones'])->orderBy('id', 'desc')->get();
            $salones = \App\Models\Salon::with('edificio')->get();
            $profesores = \App\Models\Usuario::where('rol_id', 4)->get();
            $estudiantes = \App\Models\Usuario::where('rol_id', 3)->get();
            $eventosFunciones = \App\Models\Funcion::with(['curso.salon.butacas', 'tickets'])
                ->whereHas('curso', function ($q) {
                    $q->where('tipo', 'evento');
                })
                ->get();

            return view('dashboard.admin', compact(
                'usuario',
                'solicitudesPendientes',
                'ticketsAuditorioActivos',
                'cursos',
                'salones',
                'profesores',
                'estudiantes',
                'eventosFunciones'
            ));
        }

        // Si es profesor, cargamos sus cursos programados junto con las funciones asociadas.
        // Usamos la carga diferida avanzada (eager loading) de Eloquent para traer eficientemente los datos del salón,
        // los tickets, el nombre del estudiante y el asiento/butaca asignado de una sola vez.
        if ($usuario->esProfesor()) {
            $cursosImpartidos = Curso::with(['salon', 'funciones.tickets.usuario', 'funciones.tickets.butaca'])
                ->where('profesor_id', $usuario->id)
                ->orderBy('fecha_inicio', 'desc')
                ->get();
                
            return view('dashboard.profesor', compact('usuario', 'cursosImpartidos'));
        }

        // Si es estudiante, recuperamos sus reservas activas (las que están vigentes)
        // para que las muestre en su panel principal.
        $ticketsActivos = Ticket::with(['funcion.curso.salon', 'butaca'])
            ->where('usuario_id', $usuario->id)
            ->where('estado', 'activo')
            ->orderBy('fecha_inscripcion', 'desc')
            ->get();

        // También traemos las solicitudes pendientes de verificación de Pago Móvil (Auditorio)
        $ticketsPendientes = Ticket::with(['funcion.curso.salon', 'butaca'])
            ->where('usuario_id', $usuario->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_inscripcion', 'desc')
            ->get();

        // También traemos el historial de reservas (canceladas o ya pasadas) para mantener el registro histórico.
        $historialTickets = Ticket::with(['funcion.curso.salon', 'butaca'])
            ->where('usuario_id', $usuario->id)
            ->whereNotIn('estado', ['activo', 'pendiente'])
            ->orderBy('fecha_inscripcion', 'desc')
            ->get();

        // Consultamos un servicio de clima externo con Open-Meteo para mostrar la temperatura actual en Catia la Mar.
        // Si por alguna razón la API falla, capturamos la excepción para no detener la carga del portal de la aplicación.
        $clima = [
            'temperatura' => '--',
            'condicion' => 'Desconocido',
            'icono' => '☁️'
        ];
        
        try {
            $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => 10.6,
                'longitude' => -67.03,
                'current_weather' => true
            ]);
            
            if ($response->successful()) {
                $data = $response->json('current_weather');
                $clima['temperatura'] = round($data['temperature']);
                
                $code = $data['weathercode'];
                $isDay = $data['is_day'];
                
                if ($code == 0) {
                    $clima['condicion'] = 'Despejado';
                    $clima['icono'] = $isDay ? '☀' : '☾';
                } elseif (in_array($code, [1, 2, 3])) {
                    $clima['condicion'] = 'Nublado';
                    $clima['icono'] = '☁';
                } elseif (in_array($code, [45, 48])) {
                    $clima['condicion'] = 'Niebla';
                    $clima['icono'] = '≡';
                } elseif (in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82])) {
                    $clima['condicion'] = 'Lluvia';
                    $clima['icono'] = '☂';
                } elseif (in_array($code, [95, 96, 99])) {
                    $clima['condicion'] = 'Tormenta';
                    $clima['icono'] = '⚡';
                }
            }
        } catch (\Exception $e) {
            // Si la llamada a la API externa falla, el sistema continuará funcionando normalmente y mostrará valores por defecto.
        }

        return view('dashboard.estudiante', compact('usuario', 'ticketsActivos', 'ticketsPendientes', 'historialTickets', 'clima'));
    }
}
