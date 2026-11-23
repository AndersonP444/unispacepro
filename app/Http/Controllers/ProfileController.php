<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// Este controlador se encarga de que los usuarios puedan ver y editar su información de perfil.
// Permite actualizar datos básicos, cambiar la carrera, subir una foto de perfil y cambiar la contraseña.
class ProfileController extends Controller
{
    // Carga los datos actuales de la persona logueada y obtiene la lista de carreras disponibles
    // de la universidad para que se muestren como opciones en el formulario.
    public function index()
    {
        $usuario = Auth::user();
        $carreras = \App\Models\Carrera::all();
        return view('dashboard.perfil', compact('usuario', 'carreras'));
    }

    // Procesa el formulario para guardar las actualizaciones del perfil.
    // Primero valida que los campos tengan el formato correcto (por ejemplo, el tamaño de la foto o la confirmación de la contraseña).
    // Si el usuario decide subir una nueva foto de perfil, borramos la imagen anterior del almacenamiento para ahorrar espacio.
    // Si decide cambiar la contraseña, la encriptamos antes de guardarla.
    // Al final, guardamos los datos usando Eloquent y redirigimos al perfil con un mensaje de éxito.
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre'      => 'required|string|max:50',
            'apellido'    => 'required|string|max:50',
            'telefono'    => 'nullable|string|max:11|regex:/^[0-9]+$/',
            'biografia'   => 'nullable|string|max:1500',
            'gustos'      => 'nullable|string|max:255',
            'carrera_id'  => 'nullable|exists:carreras,id',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'password'    => [
                'nullable',
                'string',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'confirmed',
            ],
        ], [
            'foto_perfil.image' => 'El archivo seleccionado debe ser una imagen válida (JPG, PNG o WEBP).',
            'foto_perfil.max'   => 'La foto de perfil no debe pesar más de 3 MB.',
            'password.min'      => 'La contraseña debe tener un mínimo de 8 caracteres.',
            'password.max'      => 'La contraseña no puede exceder los 20 caracteres.',
            'password.regex'    => 'La contraseña debe contener entre 8 y 20 caracteres, incluyendo minúsculas, mayúsculas y números.',
        ]);

        if ($request->hasFile('foto_perfil')) {
            if ($usuario->foto_perfil) {
                $oldDirect = public_path($usuario->foto_perfil);
                if (file_exists($oldDirect)) {
                    @unlink($oldDirect);
                }
                $oldStorage = public_path('storage/' . $usuario->foto_perfil);
                if (file_exists($oldStorage)) {
                    @unlink($oldStorage);
                }
            }

            $perfilesDir = public_path('images/perfiles');
            if (!is_dir($perfilesDir)) {
                @mkdir($perfilesDir, 0777, true);
            }
            $ext = $request->file('foto_perfil')->extension() ?: $request->file('foto_perfil')->getClientOriginalExtension() ?: 'jpg';
            $filename = 'perfil_' . $usuario->id . '_' . time() . '.' . $ext;
            $request->file('foto_perfil')->move($perfilesDir, $filename);

            $usuario->foto_perfil = 'images/perfiles/' . $filename;
        }

        $usuario->nombre     = $request->nombre;
        $usuario->apellido   = $request->apellido;
        $usuario->telefono   = $request->telefono;
        $usuario->biografia  = $request->biografia;
        $usuario->gustos     = $request->gustos;
        $usuario->carrera_id = $request->carrera_id;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('perfil.index')->with('success', '¡Tu perfil ha sido actualizado exitosamente!');
    }
}
