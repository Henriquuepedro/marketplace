@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4>{{ $page_title }}</h4>
                <br>
                <form method="POST" action="{{ url('/esqueci-a-senha') }}" accept-charset="utf-8" onsubmit="return false;">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="username">Digite seu e-mail</label>
                            <input id="username" name="username" type="email" class="form-control">
                        </div>
                    </div>

                    <div class="form-row justify-content-between">
                        <div class="form-group col-5">
                            <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">redefinir minha senha</button>
                        </div>
                    </div>

                </form>

                <p class="mt-5">
                    Lembrou a senha?
                    <a href="{{ url('/entrar') }}"><big>Acesse sua conta aqui</big></a>
                </p>

            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection