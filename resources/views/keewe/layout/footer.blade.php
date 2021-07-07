<!-- Footer Section Begin -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="horiz-line">
                    <button id="back-to-top" class="btn btn-sm btn-totop" data-toggle="tooltip" data-placement="bottom" title="Voltar ao topo">
                        <span class="fa fa-chevron-up"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="footer__logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('keewe/img/logo-keewe.png') }}" alt="Keewe"></a>
                </div>
            </div>
        </div>
        <div class="row">

            {!! footer_menus( $categories ) !!}

            <!--
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Categorias</h6>
                    <ul>
                        @foreach( $categories as $cat )

                            @if( ! is_null($cat->parent_id) )
                                @continue
                            @endif

                            <li><a href="{{ url('/categoria/' . $cat->slug) }}">{{ $cat->name }}</a></li>

                        @endforeach

                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Institucional</h6>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/lojas') }}">Índice de lojas</a></li>
                        @foreach( $footer_menu as $item )
                            <li><a href="{{ url('/pg/' . $item->slug) }}">{{ $item->title }}</a></li>
                        @endforeach
                        <li><a href="{{ url('/fale-conosco') }}">Fale Conosco</a></li>
                    </ul>
                </div>
            </div>
            -->

            <div class="col-lg-3 col-md-3 col-sm-4">&nbsp;</div>

            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="footer__newslatter">
                    <h6> </h6>
                    @if( auth()->guest() || ! auth()->user()->hasStore() )
                        <a href="{{ url('/cadastro') . '?t=store' }}" class="btn btn-primary btn-block">CRIE SUA LOJA!</a>
                    @endif
                    <br>
                    <span>siga-nos nas redes sociais</span>
                    <br>
                    <div class="footer__social">
                        <!-- <a href="#" class="facebook"><i class="fa fa-facebook"></i></a> -->
                        <a href="https://www.instagram.com/keewebrasil/?igshid=gni0tqsyfu7u" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a>
                        <!-- <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="youtube"><i class="fa fa-youtube-play"></i></a> -->
                        <a href="https://br.pinterest.com/keewebrasil/" class="pinterest" target="_blank"><i class="fa fa-pinterest"></i></a>
                        <a href="https://open.spotify.com/user/kf7lpsanfjboxc6cx71fvjs3d?si=777ec72b31fb4258" class="spotify" target="_blank"><i class="fa fa-spotify"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                <div class="footer__copyright__text">
                    <p>
                        Copyright &copy; {{ date('Y') }}
                        All rights reserved | Keewe
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->
