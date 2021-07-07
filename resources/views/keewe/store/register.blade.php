@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">
                    <h5>Dados de acesso</h5>
                    <form method="POST" action="{{ url('/users') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="target" value="{{ $target }}">
                        <input type="text" name="fullname" placeholder="Nome">
                        <input type="email" name="username" placeholder="Email">
                        <input type="password" name="password" placeholder="Senha">
                        <input type="password" name="password_confirmation" placeholder="Confirme a senha">

                        <p>
                            Ao clicar no botão "cadastrar" abaixo, você declara que leu,
                            compreendeu e concorda com nossos
                            <a href="{{ url('/pg/termos-de-uso') }}"><b>Termos de Uso</b></a> e com nossa
                            <a href="{{ url('/pg/politica-de-privacidade') }}"><b>Política de Privacidade</b></a>.
                        </p>

                        <div class="row justify-content-between">
                            <div class="col-5">
                                <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">cadastrar</button>
                            </div>
                        </div>
                    </form>

                    <p class="mt-5">
                        Já tem cadastro?
                        <a href="{{ url('/entrar') }}">Entre na sua conta</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
