@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ $page_title }}</h4>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">

                        <!-- Seller menu -->
                        @include('keewe.user._nav')

                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <h5 class="title2">Alterar Senha</h5>
                        <p>
                            Altere seua senha de vez em quando para aumentar a segurança da sua conta.<br>
                            Não use letras ou números sequenciais, nomes ou datas que outras pessoas podem descobrir.<br>
                            Tente não utilizar a mesma senha em diferentes sites e/ou serviços.<br>
                        </p>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="cur_pass">Senha atual</label>
                                    <input id="cur_pass" name="cur_pass" type="password" class="form-control">
                                    <small class="form-text text-muted">Digite a sua senha atual.</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="password">Nova Senha</label>
                                    <input id="password" name="password" type="password" class="form-control">
                                    <small class="form-text text-muted">Digite uma nova senha</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">Confirme a Nova Senha</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                                    <small class="form-text text-muted">Digite novamente a nova senha</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">alterar senha</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
