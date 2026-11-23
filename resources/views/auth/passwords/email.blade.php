@extends('layouts.public')
@section('title', 'Recuperar Contraseña')
@section('content')
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Recuperar Contraseña</h2>
            <p>Ingresa tu correo y te enviaremos un enlace seguro para restablecer tu clave.</p>
        </div>
        
        @if (session('status'))
            <div class="alert" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center; font-weight: bold;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico Registrado</label>
                <input type="email" id="email" name="email" required autofocus maxlength="255" placeholder="tucorreo@unispace.edu.ve">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Enviar Enlace de Recuperación</button>
        </form>

        <div class="auth-footer" style="margin-top: 1.5rem; text-align: center;">
            <p><a href="{{ route('login') }}" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">&larr; Volver a Iniciar Sesión</a></p>
        </div>
    </div>
</section>
@endsection
