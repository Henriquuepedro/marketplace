@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h4>{{ $page_title }}</h4>
            </div>
        </div>
        <div class="row cart">
            @if( count($cart->products) > 0 )
                <div class="col-md-8 col-sm-12 col-xs-12 cart-items">
                    <h5>Itens</h5>
                    @php
                        // Holds the Store ID
                        $last_store_id = null;
                        $last_store    = null;
                        $last_shipping = 0.00;
                    @endphp
                    @foreach( $cart->products as $item )
                        <!-- Check last store to show shipping -->
                        @if( ! is_null($last_store_id) && ($item->store_id != $last_store_id) )
                            <x-shipping-card :store="$last_store" :amount="$last_shipping"></x-shipping-card>
                        @endif
                        @php
                            // Get Shipping
                            if( $item->shipping > 0 )
                                $last_shipping = $item->shipping;
                        @endphp
                        <div class="card mt-1">
                            <div class="row no-gutters">
                                <div class="col-md-2 col-sm-3 col-xs-4 product-thumb">
                                    <img src="{{ asset($item->image) }}" class="card-img" alt="{{ $item->name }}">
                                </div>
                                <div class="col-md-10 col-sm-9 col-xs-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->name }}</h5>
                                        <div class="row">
                                            <div class="col-6">
                                                @if( count($item->variations) > 0 )

                                                    {!! implode(' - ', $item->variations) !!}

                                                @endif
                                            </div>
                                            <div class="col-6 text-right text-muted"><i>{{ $item->store_name }}</i></div>
                                        </div>
                                        @if( ($item->stock <= 0) || ($item->store_status != 'active') )
                                            <div class="row">
                                                <div class="col">Produto indisponível</div>
                                            </div>
                                        @else
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Preço unitário</th>
                                                        <th>Quantidade</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ fmoney( $item->unit_price ) }}</td>
                                                        <td>
                                                            {{ $item->quantity }}
                                                            &nbsp;&nbsp;&nbsp;
                                                            <div class="btn-group" role="group" aria-label="Ajuste de quantidade">
                                                                <button type="button" class="btn btn-outline-success" onclick="addOne({{ $item->id }});" data-toggle="tooltip" data-placement="bottom" title="Adicionar uma unidade">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-warning" onclick="delOne({{ $item->id }});" data-toggle="tooltip" data-placement="bottom" title="Remover uma unidade">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger" onclick="delItem({{ $item->id }});" data-toggle="tooltip" data-placement="bottom" title="Remover produto">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td>{{ fmoney( $item->subtotal ) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $last_store_id = $item->store_id;
                            $last_store = $item->store_name;
                        @endphp
                    @endforeach
                    @if( $item->stock > 0 )
                        <x-shipping-card :store="$last_store" :amount="$last_shipping"></x-shipping-card>
                    @endif
                </div>
                <div class="col-md-3">
                    @if( ($item->stock > 0) && ($item->store_status == 'active') )
                        <h5>Calcule o frete</h5>
                        <input id="zipcode" type="text" class="form-control msk-cep" value="{{ $zipcode ?? '' }}" onkeyup="calcShipping(this);">
                        <br><br>
                        <h5>Valor</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Itens:</td>
                                    <td class="text-right">{{ fmoney( $cart->subtotal ) }}</td>
                                </tr>
                                <tr>
                                    <td>Frete:</td>
                                    <td class="text-right">{{ fmoney( $cart->shipping ) }}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <th class="text-right">{{ fmoney( $cart->total ) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        <a href="{{ url('/checkout?cart='. $cart->cart_id) }}" class="btn btn-secondary btn-block">finalizar compra</a>
                    @else
                        <h5 class="text-center"><i class="fa fa-fw fa-2x fa-exclamation-triangle" style="color:#F7941D;"></i></h5>
                        
                        <p>
                            Não é possível prosseguir com seu pedido nesse momento. Por favor, tente novamente mais tarde.
                        </p>
                        <p>
                            Se o problema persistir, entre em contato conosco informando o ocorrido, 
                            juntamente com o nome do Produto <q><i><u>{{ $item->name }}</u></i></q> 
                            e o nome da Loja <q><i><u>{{ $item->store_name }}</u></i></q>.
                        </p>
                    @endif
                </div>
            @else
                <div class="col-12 mt-3">
                    <p><big>Seu carrinho de compras está vazio.</big></p>
                </div>
            @endif
        </div>
    </div>
</section>
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        // Global var
        var Fields = {
            _token: null,
            _method: 'PUT',
            action: null
        };

        $(document).ready(function(){
            //
            Fields._token = $('input[name=_token]').val();
        });

        /**
         * Add one unit to given Cart Item
         */
        function addOne( item_id )
        {
            Fields.action = 'add-one';
            var url       = BASE_URL + '/carts/' + item_id;

            Common.ajax( url, 'POST', Fields, null, onCartUpdate );
        }

        /**
         * Removes one unit to given Cart Item
         */
        function delOne( item_id )
        {
            Fields.action = 'del-one';
            var url       = BASE_URL + '/carts/' + item_id;

            Common.ajax( url, 'POST', Fields, null, onCartUpdate );
        }

        /**
         * Removes the Item from Cart
         */
        function delItem( item_id )
        {
            Fields.action = 'del-item';
            var url       = BASE_URL + '/carts/' + item_id;

            Common.ajax( url, 'POST', Fields, null, onCartUpdate );
        }

        /**
         * Calculates shipping
         */
        function calcShipping( input )
        {
            var zip = $(input).val();

            if( zip.length < 9 )
                return;

            // Zip completed
            var url = BASE_URL + '/shipping';

            Common.ajax( url, 'GET', {cep: zip}, null, onCartUpdate );
        }

        /**
         * Handler for cart update
         */
        function onCartUpdate( json )
        {
            // Get zipcode
            var zip = $('#zipcode').val();
            var url = BASE_URL + '/carrinho';

            if( zip.length == 9 )
                url += '?cep=' + zip;

            document.location.href = url;
        }
    </script>
@endsection
