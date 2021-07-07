@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4>{{ $page_title }}</h4>
                <br>
                <form method="POST" action="{{ url('/redefinir-senha') }}" accept-charset="utf-8" onsubmit="return false;">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="username">Confirme seu e-mail</label>
                            <input id="username" name="username" type="email" class="form-control" value="{{ $email ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="token">Código recebido por email</label>
                            <input id="token" name="token" type="text" class="form-control" maxlength="6">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="password">Digite uma nova senha</label>
                            <input id="password" name="password" type="password" class="form-control">
                        </div>
                    </div>

                    <div class="form-row justify-content-between">
                        <div class="form-group col-5">
                            <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">redefinir minha senha</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection