<header class="main-header">
    <a href="{{ url('/') }}" class="brand">
        <span class="brand-name">Anatomía MEDAC</span>
    </a>

    <nav class="nav-actions">
        @if (Route::has('login'))
            @auth
                <a href="{{ route('inicio') }}" class="btn-ghost">Inicio</a>
                <a href="{{ route('nuevo informe') }}" class="btn-ghost">Nuevo Informe</a>
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
