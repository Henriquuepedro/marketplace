@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4>{{ $page_title }}</h4>
                <br>
                <form method="POST" action="{{ url('/login') }}" accept-charset="utf-8" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="target" value="{{ $target ?? '' }}">
                    <input type="hidden" name="cart" value="{{ $cart ?? '' }}">

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="username">Seu E-mail</label>
                            <input id="username" name="username" type="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="password">Sua Senha</label>
                            <input id="password" name="password" type="password" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="yes" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Manter-me conectado</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row justify-content-between">
                        <div class="form-group col-5">
                            <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">acessar</button>
                        </div>
                        <div class="form-group col-5 text-right">
                            <a href="{{ url('/esqueci-a-senha') }}">esqueci minha senha</a>
                        </div>
                    </div>

                </form>

                <p class="mt-5">
                    Primeira vez por aqui?
                    <a href="{{ url('/cadastro') }}"><big>Faça seu Cadastro</big></a>
                </p>

            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection