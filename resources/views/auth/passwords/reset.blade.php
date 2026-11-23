@extends('layouts.public')
@section('title', 'Restablecer Contraseña')
@section('content')
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Crear Nueva Contraseña</h2>
            <p>Ingresa tu nueva clave de acceso para tu cuenta.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ request()->query('email') }}" required autofocus readonly maxlength="255" style="background-color: #f1f5f9; color: #64748b;">
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required minlength="8" maxlength="20" placeholder="********">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" maxlength="20" placeholder="********">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Guardar Contraseña y Entrar</button>
        </form>
    </div>
</section>
@endsection
