<!-- 
    Layout Principal de UniSpace
    Equipo de Desarrollo: Oscar (Líder Frontend), Greymel (Co-líder/Fullstack), Anderson (Base de Datos) y Diego (Backend).
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SEO Meta Tags para UniSpace -->
    <title>@yield('title', 'Inicio') | UniSpace</title>
    <meta name="description" content="Portal interactivo de reserva de salones y talleres universitarios de UniSpace.">
    
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <!-- Fuentes modernas: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Oscar y Greymel (Frontend): Estilos globales puros sin Tailwind como pidió Dayana -->
    <style>
        :root {
            --primary-color: #1e3a5f;
            --primary-hover: #0f172a;
            --secondary-color: #2563eb;
            --secondary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --text-main: #0f172a;
            --white: #ffffff;
            --shadow: 0 4px 12px -2px rgb(15 23 42 / 0.08), 0 2px 6px -2px rgb(15 23 42 / 0.05);
        }
        body {
            color: var(--text-main);
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .main-header {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 0;
            gap: 1rem;
        }
        .logo-link {
            color: var(--primary-hover);
            text-decoration: none;
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: transform 0.2s ease;
        }
        .logo-link:hover {
            transform: translateY(-1px);
        }
        .main-nav ul {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        .main-nav a {
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.94rem;
            padding: 0.5rem 0.9rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.22s ease;
        }
        .main-nav a:hover {
            color: var(--secondary-color);
            background: #eff6ff;
        }
        .main-nav a.nav-active {
            color: var(--secondary-color);
            background: #eff6ff;
            box-shadow: inset 0 0 0 1px #bfdbfe;
        }
        .main-nav svg {
            width: 17px;
            height: 17px;
        }
        .user-nav-pill {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.3rem 0.75rem 0.3rem 0.35rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
        }
        .user-nav-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }
        .user-nav-role {
            font-size: 0.7rem;
            font-weight: 600;
            color: #64748b;
            display: block;
        }
        .auth-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.45rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .auth-nav-link:hover, .auth-nav-link.active {
            color: var(--secondary-color);
            background: #eff6ff;
        }
    </style>
</head>
<body>
    <!-- HEADER SEMÁNTICO -->
    <header class="main-header">
        <div class="container header-container">
            <div class="logo-area">
                <a href="{{ route('home') }}" class="logo-link">
                    <img src="{{ asset('images/logo.png') }}" alt="UniSpace Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <span class="logo-text">Uni<span style="color: var(--secondary-color);">Space</span></span>
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="{{ route('home') }}#mapa">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 7.2c0 7.3-8 11.8-8 11.8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Mapa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('espacios') }}" class="{{ request()->routeIs('espacios') ? 'nav-active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            Espacios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cursos') }}" class="{{ request()->routeIs('cursos') ? 'nav-active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Cursos y Talleres
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('auditorio') }}" class="{{ request()->routeIs('auditorio') ? 'nav-active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Auditorio y Eventos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profesores') }}" class="{{ request()->routeIs('profesores') ? 'nav-active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Profesores
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="auth-buttons" style="align-items: center;">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 0.5rem 1.15rem; font-size: 0.9rem;">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.15rem; font-size: 0.9rem;">Registrarse</a>
                @else
                    <div class="user-nav-pill">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem; flex-shrink: 0;">
                            {{ strtoupper(substr(Auth::user()->nombre, 0, 1) . substr(Auth::user()->apellido, 0, 1)) }}
                        </div>
                        <div>
                            <span class="user-nav-name">{{ Auth::user()->nombre }}</span>
                            <span class="user-nav-role">{{ Auth::user()->esAdmin() ? 'Administrador' : (Auth::user()->esProfesor() ? 'Profesor' : 'Estudiante') }}</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.35rem;">
                        <a href="{{ route('dashboard') }}" class="auth-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('perfil.index') }}" class="auth-nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Mi Perfil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0.45rem 0.65rem; border-radius: 8px; display: flex; align-items: center; gap: 0.35rem; color: #dc2626; font-weight: 600; font-size: 0.9rem; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='transparent'" title="Cerrar Sesión">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                Salir
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <!-- MAIN (Contenido inyectado por las vistas hijas) -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER SEMÁNTICO UMC -->
    <footer class="main-footer">
        <div class="container footer-content">
            
            <div class="footer-column">
                <h3 style="color: var(--white);">Cursos</h3>
                <ul>
                    <li><a href="{{ route('cursos', ['curso' => 'Idiomas']) }}">Idiomas</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Tecnología y Numéricas']) }}">Tecnología y Numéricas</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Química']) }}">Química</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Física']) }}">Física</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3 style="color: var(--white);">Talleres/Clases</h3>
                <ul>
                    <li><a href="{{ route('cursos', ['curso' => 'Cálculo']) }}">Cálculo</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Química']) }}">Química</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Física']) }}">Física</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Programación']) }}">Programación</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Tecnología Digital']) }}">Tecnología Digital</a></li>
                    <li><a href="{{ route('cursos', ['curso' => 'Termodinámica']) }}">Termodinámica</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3 style="color: var(--white);">Espacios</h3>
                <ul>
                    <li><a href="{{ route('espacios', ['tipo' => 'aula']) }}">Salones</a></li>
                    <li><a href="{{ route('espacios', ['tipo' => 'laboratorio']) }}">Laboratorios</a></li>
                    <li><a href="{{ route('espacios', ['buscar' => 'Aula']) }}">Idiomas</a></li>
                    <li><a href="{{ route('espacios', ['tipo' => 'multimedia']) }}">Tecnología (Multimedia)</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3 style="color: var(--white);">Créditos del Sistema</h3>
                <ul>
                    <li><strong>Anderson</strong></li>
                    <li><strong>Diego</strong></li>
                    <li><strong>Oscar</strong></li>
                    <li><strong>Greymel</strong></li>
                </ul>
            </div>

        </div>
        
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <div class="footer-bottom-text">
                    <p><strong>Universidad Marítima del Caribe. RIF: G-20003807-1</strong></p>
                    <p>Av. El Ejército, Catia la Mar, Edo. La Guaira, Venezuela.</p>
                    <p>Sistema Integral de Reserva de Cupos para Cursos y Talleres</p>
                </div>
                <div class="footer-bottom-info">
                    <p>UniSpace - Facilitando el acceso a la educación y la administración de espacios.</p>
                </div>
            </div>
        </div>
    </footer>

    @include('components.chatbot')

    <!-- Scripts interactivos (Vanilla JS) -->
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
