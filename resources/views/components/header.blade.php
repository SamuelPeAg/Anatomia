<header class="header">
    <a href="{{ url('/') }}" class="logo">
        Anatomía MEDAC
    </a>

    <nav class="nav-links">
        @if (Route::has('login'))
            @auth
                <a href="{{ route('home') }}" class="btn btn-login">
                    Inicio
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-register">Cerrar Sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-login">
                    Iniciar Sesión
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-register">
                        Crear Cuenta
                    </a>
                @endif
            @endauth
        @endif
    </nav>
</header>
