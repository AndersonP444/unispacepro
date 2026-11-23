@extends('layouts.public')

@section('title', 'Mi Perfil')

@section('content')
<section class="profile-section">
    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
                <div><strong>¡Hecho!</strong> {{ session('success') }}</div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: #0f5132; cursor: pointer; line-height: 1;">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <ul style="margin-left: 1.5rem; margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-layout">
            <!-- Columna Izquierda: Tarjeta de Perfil -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-banner"></div>
                    
                    <div class="profile-avatar-container">
                        @if($usuario->fotoPerfilUrl())
                            <img src="{{ $usuario->fotoPerfilUrl() }}" alt="{{ $usuario->nombreCompleto() }}" class="profile-avatar" id="avatar-preview-img">
                        @else
                            <div class="profile-avatar-placeholder" id="avatar-preview-placeholder">
                                {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                            </div>
                        @endif
                        <label for="foto_perfil" class="avatar-upload-btn" title="Cambiar foto de perfil">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </label>
                    </div>

                    <div class="profile-info">
                        <h3>{{ $usuario->nombre }} {{ $usuario->apellido }}</h3>
                        <p class="role-badge">{{ $usuario->esProfesor() ? 'Profesor Docente' : ($usuario->esAdmin() ? 'Administrador' : 'Estudiante') }} | C.I: {{ $usuario->cedula }} @if($usuario->carrera_id) | {{ $usuario->carrera->nombre }} @endif</p>
                        
                        @if($usuario->biografia)
                            <div class="bio-text">{{ $usuario->biografia }}</div>
                        @endif
                        
                        @if($usuario->gustos)
                            <div class="likes-container">
                                @foreach(explode(',', $usuario->gustos) as $gusto)
                                    <span class="like-tag">{{ trim($gusto) }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($usuario->esProfesor())
                            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 0.5rem;">
                                <a href="{{ route('profesores', ['profesor' => $usuario->nombreCompleto()]) }}" class="btn btn-outline btn-sm" style="width: 100%;">
                                    Ver mi tarjeta en Profesores
                                </a>
                                <a href="{{ route('cursos', ['profesor' => $usuario->nombreCompleto()]) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                                    Ver mis Cursos y Talleres
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario de Edición -->
            <div class="profile-form-container">
                <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h4>Foto de Perfil</h4>
                        <div class="form-group">
                            <label for="foto_perfil">Subir o actualizar foto (JPG, PNG o WEBP, máx. 3 MB)</label>
                            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/jpeg,image/png,image/jpg,image/webp">
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Información Personal</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" required maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $usuario->apellido) }}" required maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}" maxlength="11" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="form-group">
                                <label for="carrera_id">Carrera</label>
                                <select name="carrera_id" id="carrera_id" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #ced4da; border-radius: 8px; font-family: var(--font-main); background-color: #f8f9fa;">
                                    <option value="">Seleccione una carrera</option>
                                    @foreach($carreras as $c)
                                        <option value="{{ $c->id }}" {{ old('carrera_id', $usuario->carrera_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Perfil Social</h4>
                        <div class="form-group">
                            <label for="biografia">Biografía</label>
                            <textarea name="biografia" id="biografia" rows="3" placeholder="Cuéntanos un poco sobre ti...">{{ old('biografia', $usuario->biografia) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="gustos">Gustos / Intereses (Separados por coma)</label>
                            <input type="text" name="gustos" id="gustos" value="{{ old('gustos', $usuario->gustos) }}" placeholder="Ej: Programación, IA, Videojuegos, Lectura">
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Seguridad (Opcional)</h4>
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">Deja en blanco si no deseas cambiar tu contraseña.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="password">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" minlength="8" maxlength="20" placeholder="Entre 8 y 20 caracteres">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" maxlength="20">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</section>
@endsection

@push('scripts')
<style>
    .profile-section {
        padding: 3rem 0;
        min-height: calc(100vh - 150px);
    }
    
    .profile-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
        gap: 2rem;
        align-items: start;
    }

    .profile-sidebar,
    .profile-form-container {
        min-width: 0;
        max-width: 100%;
    }

    /* Columna Izquierda */
    .profile-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
        text-align: center;
        position: relative;
        max-width: 100%;
    }

    .profile-banner {
        height: 120px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
    }

    .profile-avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: -60px auto 1rem auto;
    }

    .profile-avatar, .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid var(--white);
        object-fit: cover;
        background-color: var(--light-color);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .profile-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--primary-color);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid var(--white);
        transition: var(--transition);
    }

    .avatar-upload-btn:hover {
        background: var(--primary-hover);
        transform: scale(1.1);
    }

    .profile-info {
        padding: 1rem 1.75rem 2rem 1.75rem;
        min-width: 0;
        max-width: 100%;
    }

    .profile-info h3 {
        margin-bottom: 0.2rem;
        color: var(--dark-color);
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .role-badge {
        font-size: 0.85rem;
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
    }

    .bio-text {
        color: #334155;
        margin-bottom: 1.5rem;
        font-size: 0.92rem;
        line-height: 1.6;
        text-align: left;
        background: #f8fafc;
        padding: 0.9rem 1.1rem;
        border-radius: 10px;
        border-left: 3px solid var(--primary-color);
        white-space: pre-line;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
    }

    .likes-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }

    .like-tag {
        background: rgba(13,110,253,0.1);
        color: var(--primary-color);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Columna Derecha */
    .profile-form-container {
        background: var(--white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow);
    }

    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eaedf1;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 1.5rem;
        padding-bottom: 0;
    }

    .form-section h4 {
        color: var(--primary-hover);
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--dark-color);
    }

    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-family: var(--font-main);
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: var(--white);
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }

    .form-actions {
        text-align: right;
        margin-top: 2rem;
    }

    @media (max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('foto_perfil');
        if (!input) return;
        input.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                const container = document.querySelector('.profile-avatar-container');
                let img = document.getElementById('avatar-preview-img');
                const placeholder = document.getElementById('avatar-preview-placeholder');
                if (placeholder) placeholder.remove();
                if (!img && container) {
                    img = document.createElement('img');
                    img.id = 'avatar-preview-img';
                    img.className = 'profile-avatar';
                    container.prepend(img);
                }
                if (img) img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
