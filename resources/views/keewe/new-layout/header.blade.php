<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand flex-grow-1" href="{{ url('/') }}">
                @if( is_home() )
                    <img src="{{ asset('keewe/img/logo-keewe-white.png') }}" alt="Kewwe">
                @else
                    <img src="{{ asset('keewe/img/logo-keewe.png') }}" alt="Keewe">
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse flex-fill" id="navbarsExample07">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="fa fa-heart-o"></span> Minha Wishlist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="fa fa-shopping-bag"></span>
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/entrar') }}">
                                <span class="fa fa-user"></span> Entrar
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fa fa-user"></span> {{ auth()->user()->fullname }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Minha conta</a>
                                <a class="dropdown-item" href="#">Minha loja</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('/logout') }}">Sair</a>
                            </div>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>
</header>