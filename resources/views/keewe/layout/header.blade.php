<!-- Offcanvas Menu Begin -->
<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas__close">+</div>
    <ul class="offcanvas__widget">
        <li>
            <a href="{{ url('/wishlist') }}">
                <span class="fa fa-heart-o"></span>
            </a>
        </li>
        <li>
            <a href="{{ url('/carrinho') }}">
                <span class="fa fa-shopping-cart"></span>
                @if( $cart_items > 0 )
                    <div class="tip">{{ $cart_items }}</div>
                @endif
            </a>
        </li>
    </ul>
    <div class="offcanvas__logo">
        <a href="{{ url('/') }}"><img src="{{ asset('keewe/img/logo-keewe.png') }}" alt="Keewe"></a>
    </div>
    <div id="mobile-menu-wrap"></div>
    <div class="offcanvas__auth">
        @guest
            <a href="{{ url('/entrar') }}">
                <span class="fa fa-user"></span> Entrar
            </a>
            <a href="{{ url('/cadastro') }}"> Cadastrar</a>
        @endguest

        @auth
            <span class="fa fa-user"></span> {{ auth()->user()->fullname }} |
            <a href="{{ url('/logout') }}"> SAIR </a>
        @endauth
    </div>
</div>
<!-- Offcanvas Menu End -->

<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="header__logo">
                    <a href="{{ url('/') }}">
                        @if( is_home() )
                            <img src="{{ asset('keewe/img/logo-keewe-white.png') }}" alt="Kewwe">
                        @else
                            <img src="{{ asset('keewe/img/logo-keewe.png') }}" alt="Keewe">
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="header__right">
                    <ul class="header__right__widget">
                        <li>
                            <a href="{{ url('/wishlist') }}">
                                <span class="fa fa-heart-o"></span>
                                Minha Wishlist
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/carrinho') }}">
                                <span class="fa fa-shopping-cart"></span>
                                @if( $cart_items > 0 )
                                    <div class="tip">{{ $cart_items }}</div>
                                @endif
                            </a>
                        </li>
                    </ul>
                    <div class="header__right__auth">
                        @guest
                            <a href="{{ url('/entrar') }}">
                                <span class="fa fa-user"></span> Entrar
                            </a>
                            <a href="{{ url('/cadastro') }}"> Cadastrar</a>
                        @endguest

                        @auth
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fa fa-user"></span> {{ auth()->user()->fullname }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    @if( $user->isMaster() )
                                        <a class="dropdown-item" href="{{ url('/dashboard') }}">Painel de Controle</a>
                                    @else
                                        <!-- Common User links -->
                                        <a class="dropdown-item" href="{{ url('/minha-conta') }}">Minha conta</a>
                                        <a class="dropdown-item" href="{{ url('/meus-pedidos') }}">Meus pedidos</a>
                                        <!-- Store -->
                                        @if( $user->hasStore() )
                                            <a class="dropdown-item" href="{{ url('/minha-loja') }}">Minha loja</a>
                                        @else
                                            <a class="dropdown-item" href="{{ url('/criar-loja') }}">Criar loja</a>
                                        @endif
                                    @endif

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/logout') }}">Sair</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->