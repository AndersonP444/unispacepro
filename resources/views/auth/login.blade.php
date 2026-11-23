@extends('layouts.public')

@section('title', 'Iniciar Sesión')

@section('content')
<section class="auth-section">
    <div class="auth-container" style="max-width: 900px; padding: 0; overflow: hidden; display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
        <div style="background: linear-gradient(135deg, rgba(15,23,42,0.88), rgba(30,58,95,0.85)), url('{{ asset('images/carousel/edificio_nautica.jpg') }}') center/cover no-repeat; color: white; padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 2rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="UniSpace Logo" style="height: 42px; width: auto;">
                    <strong style="font-size: 1.4rem; letter-spacing: -0.02em;">UniSpace</strong>
                </div>
                <h3 style="font-size: 1.65rem; color: #ffffff; margin-bottom: 0.85rem; line-height: 1.25;">Bienvenido al Portal Académico UMC</h3>
                <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6;">Gestiona tus reservas de aulas, laboratorios y talleres universitarios de forma rápida, segura y en tiempo real.</p>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.18); padding-top: 1.2rem; font-size: 0.82rem; color: #cbd5e1;">
                Universidad Marítima del Caribe • Catia la Mar
            </div>
        </div>
        <div style="padding: 3rem 2.5rem;">
        <div class="auth-header" style="text-align: left; margin-bottom: 1.8rem;">
            <h2>Iniciar Sesión</h2>
            <p>Accede a tu cuenta de UniSpace para gestionar tus reservas.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus maxlength="255" placeholder="ejemplo@unispace.edu.ve">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required minlength="8" maxlength="20" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}" title="Debe contener entre 8 y 20 caracteres, incluyendo minúsculas, mayúsculas y números" placeholder="********" style="padding-right: 40px; width: 100%;">
                    <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6c757d;" title="Mostrar/Ocultar Contraseña">
                        <svg id="eyeIcon" viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <small style="display: block; margin-top: 0.35rem; font-size: 0.78rem; color: #64748b;">Entre 8 y 20 caracteres, combinando minúsculas, mayúsculas y números.</small>
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div class="checkbox-group" style="margin: 0;">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Mantener sesión iniciada</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size: 0.9rem; color: var(--primary-color); text-decoration: none; font-weight: bold; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Verificación de seguridad CAPTCHA -->
            <div class="form-group" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.9rem 1rem;">
                <label for="captcha" style="margin-bottom: 0.55rem; display: flex; justify-content: space-between; align-items: center;">
                    <span>Verificación de Seguridad (CAPTCHA)</span>
                    <button type="button" id="btn-refresh-captcha" style="background: none; border: none; color: var(--secondary-color); font-size: 0.8rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                        Cambiar código
                    </button>
                </label>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <img id="captcha-img" src="{{ $captchaImg ?? '' }}" alt="Código CAPTCHA" style="height: 48px; width: 160px; border-radius: 8px; border: 1px solid #94a3b8; user-select: none;">
                    <input type="text" id="captcha" name="captcha" required maxlength="5" autocomplete="off" placeholder="Escribe los 5 caracteres" style="flex: 1; min-width: 140px; text-transform: uppercase; font-weight: 700; letter-spacing: 2px; text-align: center;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </form>

        <div class="auth-footer">
            <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
        </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                if (type === 'text') {
                    // Eye off (slash)
                    eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                } else {
                    // Eye open
                    eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                }
            });
        }

        const refreshBtn = document.getElementById('btn-refresh-captcha');
        const captchaImg = document.getElementById('captcha-img');
        const captchaInput = document.getElementById('captcha');

        if (refreshBtn && captchaImg) {
            refreshBtn.addEventListener('click', function () {
                fetch('{{ route('captcha.refresh') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.image) {
                            captchaImg.src = data.image;
                            if (captchaInput) {
                                captchaInput.value = '';
                                captchaInput.focus();
                            }
                        }
                    });
            });
        }
    });
</script>
@endpush
@endsection
