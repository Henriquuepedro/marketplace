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
                        <h5 class="title2">Dados Pessoais</h5>
                        <p>
                            Mantenha sempre seus dados atualizados. Nunca divulgue sua senha de acesso à ninguém!
                            <br>
                        </p>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="fullname">Nome Completo</label>
                                    <input id="fullname" name="fullname" type="text" class="form-control" value="{{ $user->fullname ?? '' }}">
                                    <small class="form-text text-muted">Digite seu nome e sobrenome</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">E-mail</label>
                                    <input id="username" name="username" type="email" class="form-control" readonly value="{{ $user->username ?? '' }}">
                                    <small class="form-text text-muted">Seu e-mail não pode ser alterado</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col"><hr></div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">atualizar dados</button>
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
