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
                <div class="col-8 cart-items">
                    <h5>Itens</h5>
                    @foreach( $cart->products as $item )
                        <div class="card mb-4">
                            <div class="row no-gutters">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 product-thumb">
                                    <img src="{{ asset($item->image) }}" class="card-img" alt="...">
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->name }}</h5>
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
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ fmoney( $item->subtotal ) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-4">
                    <h5>Endereço de Entrega</h5>
                    @if( count($user->addresses) === 0 )
                        <p>Nenhum endereço encontrado</p>
                        <a href="#" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#addr-modal">adicionar endereço</a>
                    @else
                        <address>
                            {{ $user->addresses[0]->address }}, {{ $user->addresses[0]->number }}
                            @if( $user->addresses[0]->complement )
                                - {{ $user->addresses[0]->complement }}
                            @endif
                            <br>
                            {{ $user->addresses[0]->neighborhood }} <br>
                            {{ $user->addresses[0]->city }} - {{ $user->addresses[0]->state->code }} <br>
                            {{ $user->addresses[0]->zipcode }}
                        </address>
                    @endif

                    <br>

                    <h5>Forma de Pagamento</h5>
                    <form method="POST" action="{{ url('/checkout') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $cart->cart_id }}">
                        <ul class="nav nav-tabs" id="payments" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="cc-tab" data-toggle="tab" href="#cc" role="tab" aria-controls="cc" aria-selected="false">Cartão de crédito</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="bb-tab" data-toggle="tab" href="#bb" role="tab" aria-controls="bb" aria-selected="false">Boleto bancário</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pay-content">
                            <div class="tab-pane fade" id="cc" role="tabpanel" aria-labelledby="cc-tab">
                                <input type="hidden" name="payment_method" value="credit_card">
                                <input type="hidden" name="card_hash" value="">

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="card_number">Número do cartão</label>
                                        <input id="card_number" name="card_number" type="text" class="form-control msk-cc" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="card_holder_name">Nome (como escrito no cartão)</label>
                                        <input id="card_holder_name" name="card_holder_name" type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="card_expiration">Expiração (MM/AAAA)</label>
                                        <input id="card_expiration" name="card_expiration" type="text" class="form-control msk-mmyy" value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="card_cvv">Código de segurança</label>
                                        <input id="card_cvv" name="card_cvv" type="text" class="form-control msk-cvv" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bb" role="tabpanel" aria-labelledby="bb-tab">
                                <input type="hidden" name="payment_method" value="boleto">
                                <p>Você receberá o boleto e código de barras após confirmar sua compra</p>
                            </div>
                        </div>

                        <h5>Comprador</h5>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="doc_cpf">Número do CPF</label>
                                <input id="doc_cpf" name="doc_cpf" type="text" class="form-control msk-cpf" value="">
                            </div>
                            <div class="form-group col-6">
                                <label for="phone">Telefone</label>
                                <input id="phone" name="phone" type="text" class="form-control msk-phone" value="">
                            </div>
                            <div class="form-group col-12">
                                <small id="help" class="form-text text-muted">
                                    Nosso gateway de pagamento exige o CPF e telefone do comprador.
                                </small>
                            </div>
                        </div>
                    </form>

                    <br>

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
                    <button type="button" class="btn btn-secondary btn-block" onclick="paymentConfirm(event);">confirmar compra</button>
                </div>
            @else
                <div class="col-12 mt-3">
                    <p><big>Seu carrinho de compras está vazio.</big></p>
                </div>
            @endif
        </div>
    </div>
</section>
<!-- ADDRESS: Vertically centered scrollable modal -->
<div class="modal fade" id="addr-modal" tabindex="-1" aria-labelledby="addr-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addr-modal-title">Adicionar endereço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('/addresses') }}" accept-charset="utf-8" onsubmit="return false;">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="address_id" value="{{ $address_id ?? '' }}">
                    <input type="hidden" name="type" value="shipping">
                    <input type="hidden" name="country_id" value="30">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="Common.formPost(this, onAddressAdded);">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script src="https://assets.pagar.me/pagarme-js/4.5/pagarme.min.js"></script>
    <script>
        // Vars
        var EncKey = '{{ $ekey }}';

        function paymentConfirm( event )
        {
            event.preventDefault();

            var payment_method = getCurrentPaymentMethod();

            console.log( payment_method );

            // Get payment method
            if( payment_method === 'credit_card' )
            {
                var _card = {
                    card_holder_name: $('#card_holder_name').val(),
                    card_expiration_date: $('#card_expiration').val(),
                    card_number: $('#card_number').val(),
                    card_cvv: $('#card_cvv').val()
                };

                // Validates card data
                var checked = validateCard( _card );

                if( ! checked )
                    return;

                // Get card hash
                pagarme.client.connect({ encryption_key: EncKey })
                    .then(client => client.security.encrypt(_card))
                    .then(card_hash => {
                        // Put card hash
                        $('input[name=card_hash]').val(card_hash)
                        // Submit form
                        submitPurchase();
                    });
            }
            else
            {
                // Submit form
                submitPurchase();
            }

            // Form submit return
            return false;
        }

        /**
         * Submit purchase
         */
        function submitPurchase()
        {
            console.log( 'Submitting' );

            var payment_method = getCurrentPaymentMethod();

            var url  = BASE_URL + '/checkout';
            var args = {
                cart_id: $('input[name=cart_id]').val(),
                pay_method: payment_method,
                card_hash: $('input[name=card_hash]').val(),
                doc_cpf: $('input[name=doc_cpf]').val(),
                phone: $('input[name=phone]').val(),
            };

            Common.ajax( url, 'POST', args, null, onConfirmPurchase );
        }

        /**
         * Handler for purchase confirm
         */
        function onConfirmPurchase( json )
        {
            Common.responseHandler( json );

            if( json.success !== true )
                return;
        }

        /**
         * Validates the Credit Card using PagarMe library
         * @return Boolean
         */
        function validateCard( _card )
        {
            //console.log( _card );

            // Call validations
            var validate = pagarme.validate( {card: _card} );

            //console.log( validate );

            // Check fields ---------------------------------------------------
            // Card Number
            if( ! validate.card.card_number )
            {
                Common.notifyError( 'O número do cartão não é válido' );
                return false;
            }

            // Card Holder Name
            if( ! validate.card.card_holder_name )
            {
                Common.notifyError( 'O nome do titular do cartão não é válido' );
                return false;
            }

            // Card Expiration Month
            if( ! validate.card.card_expiration_date )
            {
                Common.notifyError( 'A data de expiração do cartão não é válida' );
                return false;
            }

            // Card CVV
            if( ! validate.card.card_cvv )
            {
                Common.notifyError( 'O código de segurança do cartão não é válido' );
                return false;
            }

            return true;
        }

        /**
         * Returns the current payment method
         * @return String
         */
        function getCurrentPaymentMethod()
        {
            if( $('#cc').is(':visible') )
                return 'credit_card';

            return 'boleto';
        }

        /**
         * Handler for new added Address
         */
        function onAddressAdded( json )
        {
            Common.responseHandler( json );

            if( json.success !== true )
                return;

            // Reload page
            document.location.reload();
        }
    </script>
@endsection
