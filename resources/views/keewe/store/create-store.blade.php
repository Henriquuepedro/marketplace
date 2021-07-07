@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">
                    <form method="POST" action="{{ url('/store') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <input type="hidden" name="email" value="{{ $user->username }}">
                        <fieldset>
                            <legend>Informações da loja</legend>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" name="cnpj" placeholder="CPF / CNPJ" class="msk-doc">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="name" placeholder="Nome Fantasia">
                                </div>
                                <div class="col-12">
                                    <input type="text" name="business_name" placeholder="Razão Social">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Informações adicionais</legend>
                            <input type="hidden" name="type" value="billing">
                            <input type="hidden" name="country_id" value="30">
                            <div class="row">
                                <div class="col-12">
                                    <input type="text" name="zipcode" placeholder="CEP" class="msk-cep" onchange="Common.getAddress(this);">
                                </div>
                                <div class="col-9">
                                    <input type="text" name="address" placeholder="Endereço">
                                </div>
                                <div class="col-3">
                                    <input type="text" name="number" placeholder="Número" class="msk-int">
                                </div>
                                <div class="col-5">
                                    <input type="text" name="complement" placeholder="Complemento">
                                </div>
                                <div class="col-7">
                                    <input type="text" name="neighborhood" placeholder="Bairro">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="city" placeholder="Cidade">
                                </div>
                                <div class="col-6">
                                    <select name="state_id" palceholder="Estado" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options( 'App\Models\Location\State', 'name', 'id', 'name' ) !!}
                                    </select>
                                </div>
                            </div>
                        </fieldset>
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
