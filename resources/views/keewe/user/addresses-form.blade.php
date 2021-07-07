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

                        <!-- User menu -->
                        @include('keewe.user._nav')

                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <h5 class="title2">{{ $page_title }}</h5>
                        <p>&nbsp;</p>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif
                            <input type="hidden" name="id" value="{{ $address->id ?? '' }}">
                            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="country_id" value="30">
                            <input type="hidden" name="origin" value="adm">

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="zipcode">CEP</label>
                                    <input id="zipcode" name="zipcode" type="text" class="form-control msk-cep" value="{{ $address->zipcode ?? '' }}" onchange="Common.getAddress(this);">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="address">Endereço</label>
                                    <input id="address" name="address" type="text" class="form-control" value="{{ $address->address ?? '' }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Número</label>
                                    <input id="number" name="number" type="text" class="form-control msk-int" value="{{ $address->number ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="complement">Complemento</label>
                                    <input id="complement" name="complement" type="text" class="form-control" value="{{ $address->complement ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="neighborhood">Bairro</label>
                                    <input id="neighborhood" name="neighborhood" type="text" class="form-control" value="{{ $address->neighborhood ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">Cidade</label>
                                    <input id="city" name="city" type="text" class="form-control" value="{{ $address->city ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state_id">Estado</label>
                                    <select id="state_id" name="state_id" palceholder="Estado" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options( 'App\Models\Location\State', 'name', 'id', 'name', ($address->state_id ?? null) ) !!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        {{ ($action === 'update' ? 'atualizar' : 'cadastrar') . ' endereço' }}
                                    </button>
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
