<header class="main-header">
    <a href="{{ url('/') }}" class="brand">
        <svg class="logo-icon" width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="40" height="40" rx="8" fill="#0234AB"/>
            <path d="M20 10L28 15V25L20 30L12 25V15L20 10Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="20" cy="20" r="3" fill="white"/>
        </svg>
        <span class="brand-name">Anatomía MEDAC</span>
    </a>

    <nav class="nav-actions">
        @if (Route::has('login'))
            @auth
                <a href="{{ route('inicio') }}" class="btn-ghost">Inicio</a>
                <a href="{{ route('revision') }}" class="btn-ghost">Revisión</a>
                {{-- <a href="{{ route('nuevo informe') }}" class="btn-ghost">Nuevo Informe</a> --}}
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-premium">Cerrar Sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Iniciar sesion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-premium">Registro</a>
                @endif
            @endauth
        @endif
    </nav>
</header>
