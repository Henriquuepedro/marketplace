@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ $page_title }} <small class="text-muted">Administração</small></h4>
                <br>
                <div class="row">
                    <div class="col-md-3">

                        <!-- Seller menu -->
                        @include('keewe.seller._nav')

                    </div>
                    <div class="col-md-9">
                        <h5 class="title2">Dados da Loja</h5>
                        <p>
                            Preencha os dados de sua loja abaixo.
                            Não esqueça de preencher o campo CEP corretamente.
                            <br>
                            Campos marcados com [<span class="required">*</span>] são obrigatórios.
                            <br><br>
                        </p>
                        <form method="POST" action="{{ url('/minha-loja/' . $store->id) }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $store->id ?? '' }}">
                            <input type="hidden" name="user_id" value="{{ $store->user_id ?? '' }}">
                            <input type="hidden" name="address_id" value="{{ $store->address_id ?? '' }}">
                            <input type="hidden" name="type" value="billing">
                            <input type="hidden" name="country_id" value="30">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="name">Nome da Loja (Nome Fantasia) <span class="required">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{ $store->name ?? '' }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="slogan">Apresentação da Loja</label>
                                    <textarea id="slogan" name="slogan" class="form-control">{{ $store->slogan ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="business_name">Razão Social <span class="required">*</span></label>
                                    <input id="business_name" name="business_name" type="text" class="form-control" value="{{ $store->business_name ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cnpj">CPF/CNPJ <span class="required">*</span></label>
                                    <input id="cnpj" name="cnpj" type="text" class="form-control msk-doc" value="{{ $store->cnpj ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail de contato <span class="required">*</span></label>
                                    <input id="email" name="email" type="email" class="form-control" value="{{ $store->email ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Telefone de contato</label>
                                    <input id="phone" name="phone" type="text" class="form-control msk-phone" value="{{ $store->phone ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="zipcode">CEP</label>
                                    <input id="zipcode" name="zipcode" type="text" class="form-control msk-cep" value="{{ $store->address->zipcode ?? '' }}" onchange="Common.getAddress(this);">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="address">Endereço</label>
                                    <input id="address" name="address" type="text" class="form-control" value="{{ $store->address->address ?? '' }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Número</label>
                                    <input id="number" name="number" type="text" class="form-control msk-int" value="{{ $store->address->number ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="complement">Complemento</label>
                                    <input id="complement" name="complement" type="text" class="form-control" value="{{ $store->address->complement ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="neighborhood">Bairro</label>
                                    <input id="neighborhood" name="neighborhood" type="text" class="form-control" value="{{ $store->address->neighborhood ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">Cidade</label>
                                    <input id="city" name="city" type="text" class="form-control" value="{{ $store->address->city ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state_id">Estado</label>
                                    <select id="state_id" name="state_id" palceholder="Estado" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options( 'App\Models\Location\State', 'name', 'id', 'name', ($store->address->state_id ?? null) ) !!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">atualizar</button>
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
