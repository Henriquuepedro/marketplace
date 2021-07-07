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
                <div class="col-md-7 cart-items">
                    <h5>Itens</h5>
                    @php
                        // Holds the Store ID
                        $last_store_id = null;
                        $last_store    = null;
                        $last_shipping = 0.00;
                    @endphp
                    @foreach( $cart->products as $item )
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
                        @php
                            $last_store_id = $item->store_id;
                            $last_store = $item->store_name;
                        @endphp
                    @endforeach
                    <x-shipping-card :store="$last_store" :amount="$last_shipping"></x-shipping-card>
                </div>
                <div class="col-md-5">
                    <h5>Endereço de Entrega <span class="required">*</span></h5>
                    @if( count( $addresses ) === 0 )
                        <p>Nenhum endereço encontrado</p>
                        <a href="#" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#addr-modal">adicionar endereço</a>
                    @else
                        <select id="shipping-address" class="form-control">
                            @foreach( $addresses as $addr )
                                <option value="{{ $addr->id }}" {{ ($addr->id == $cart->address_id) ? 'selected' : '' }}>
                                    {{ $addr->address .', '. $addr->number .' - '. $addr->city }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <br><br>

                    <h5>Pagamento <span class="required">*</span></h5>
                    <form method="POST" action="{{ url('/checkout') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $cart->cart_id }}">
                        <ul class="nav nav-tabs" id="payments" role="tablist">
                            <li class="nav-item active" role="presentation">
                                <a class="nav-link active" id="cc-tab" data-toggle="tab" href="#cc" role="tab" aria-controls="cc" aria-selected="false">Cartão de crédito</a>
                            </li>
                            <!--
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="bb-tab" data-toggle="tab" href="#bb" role="tab" aria-controls="bb" aria-selected="false">Boleto bancário</a>
                            </li>
                            -->
                        </ul>
                        <div class="tab-content" id="pay-content">
                            <div class="tab-pane fade show active" id="cc" role="tabpanel" aria-labelledby="cc-tab">
                                <input type="hidden" name="payment_method" value="credit_card">
                                <input type="hidden" name="card_hash" value="">

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="card_number">Número do cartão <span class="required">*</span></label>
                                        <input id="card_number" name="card_number" type="text" class="form-control msk-cc" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="card_holder_name">Nome (como escrito no cartão) <span class="required">*</span></label>
                                        <input id="card_holder_name" name="card_holder_name" type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="card_expiration">Expiração (MM/AA) <span class="required">*</span></label>
                                        <input id="card_expiration" name="card_expiration" type="text" class="form-control msk-mmyy" value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="card_cvv">Código de segurança <span class="required">*</span></label>
                                        <input id="card_cvv" name="card_cvv" type="text" class="form-control msk-cvv" value="">
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="tab-pane fade" id="bb" role="tabpanel" aria-labelledby="bb-tab">
                                <input type="hidden" name="payment_method" value="boleto">
                                <p>Você receberá o boleto e código de barras após confirmar sua compra</p>
                            </div>
                            -->
                        </div>

                        <!-- <h5>Comprador</h5> -->
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="doc_cpf">CPF do titular <span class="required">*</span></label>
                                <input id="doc_cpf" name="doc_cpf" type="text" class="form-control msk-cpf" value="">
                            </div>
                            <div class="form-group col-6">
                                <label for="phone">Telefone do titular <span class="required">*</span></label>
                                <input id="phone" name="phone" type="text" class="form-control msk-phone" value="">
                            </div>
                            <!--
                            <div class="form-group col-12">
                                <small id="help" class="form-text text-muted">
                                    Nosso gateway de pagamento exige o CPF e telefone do comprador.
                                </small>
                            </div>
                            -->
                        </div>
                    </form>

                    <br>

                    <h5>Cupom de desconto</h5>
                    <form method="POST" action="#" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $cart->cart_id }}">
                        <div class="form-row">
                            <div class="form-group col-8">
                                <input id="coupon" name="coupon" type="text" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <button type="button" class="btn btn-secondary btn-block" onclick="validateCoupon(this);">validar</button>
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
                    <button type="button" id="pay-btn" class="btn btn-secondary btn-block" onclick="paymentConfirm(event);">confirmar compra</button>
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
<!-- The overlay -->
<!-- <div id="overlay"></div> -->
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script src="https://assets.pagar.me/pagarme-js/4.5/pagarme.min.js"></script>
    <script>
        // Vars
        var EncKey = '{{ $ekey }}';

        // Global var
        var Fields = {
            _token: null,
            _method: 'PUT',
            action: null,
            cart_id: '{{ $cart_id }}'
        };

        $(document).ready(function(){
            //
            //overlay(false);

            Fields._token = $('input[name=_token]').val();

            $('#shipping-address').off().on('change', function(){
                //
                Fields.action = 'set-address';
                Fields.address = $(this).val();

                var url = BASE_URL + '/carts/' + Fields.cart_id;

                Common.ajax( url, 'POST', Fields, null, onSetAddress );
            });
        });

        /**
         * Reload after select address
         */
        function onSetAddress( json )
        {
            document.location.reload();
        }

        /**
         * Payment confirmation
         */
        function paymentConfirm( event )
        {
            event.preventDefault();

            Common.disableButton( '#pay-btn' );

            //overlay(true);

            var payment_method = getCurrentPaymentMethod();

            //console.log( payment_method );

            // Get payment method
            if( payment_method === 'credit_card' )
            {
                var validated = validateForm();

                if( validated == false )
                {
                    Common.enableButton( '#pay-btn' );
                    return;
                }

                var _card = {
                    card_holder_name: $('#card_holder_name').val(),
                    card_expiration_date: $('#card_expiration').val(),
                    card_number: $('#card_number').val(),
                    card_cvv: $('#card_cvv').val()
                };

                // Validates card data
                var checked = validateCard( _card );

                if( ! checked )
                {
                    //overlay(false);
                    Common.enableButton( '#pay-btn' );
                    return;
                }

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
                //submitPurchase();
            }

            // Form submit return
            return false;
        }

        /**
         * Validate form fields
         */
        function validateForm()
        {
            var fields = {
                shipping_address: $('#shipping-address').val(),
                card_holder_name: $('#card_holder_name').val(),
                card_expiration_date: $('#card_expiration').val(),
                card_number: $('#card_number').val(),
                card_cvv: $('#card_cvv').val(),
                card_cpf: $('#doc_cpf').val(),
                card_phone: $('#phone').val()
            };

            // Shipping Address
            if( ! fields.shipping_address )
            {
                Common.notifyError('Por favor, selecione o Endereço de Entrega.');
                return false;
            }

            // Card Holder Name
            if( ! fields.card_holder_name )
            {
                Common.notifyError('Por favor, preencha corretamente o Nome do Titular do Cartão.');
                return false;
            }

            // Expiration data
            if( ! fields.card_expiration_date )
            {
                Common.notifyError('Por favor, preencha corretamente a data de Expiração do Cartão.');
                return false;
            }

            // Card Number
            if( ! fields.card_number )
            {
                Common.notifyError('Por favor, preencha corretamente o número do Cartão de Crédito.');
                return false;
            }

            // Card Number
            if( ! fields.card_cvv )
            {
                Common.notifyError('Por favor, preencha corretamente o Código de segurança do cartão.');
                return false;
            }

            // CPF
            if( ! fields.card_cpf )
            {
                Common.notifyError('Por favor, preencha o CPF do Titular do cartão.');
                return false;
            }

            // Phone
            if( ! fields.card_phone )
            {
                Common.notifyError('Por favor, preencha o Telefone do Titular do cartão.');
                return false;
            }
        }

        /**
         * Submit purchase
         */
        function submitPurchase()
        {
            //console.log( 'Submitting' );

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
            //overlay(false);
            Common.enableButton( '#pay-btn' );

            Common.saveHandler( json );
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

        function overlay( _show )
        {
            if( _show )
                $('#overlay').fadeIn();
            else
                $('#overlay').fadeOut();
        }

        function validateCoupon( _sender )
        {
            // Get code
            var _code = $('#coupon').val();

            console.log('Validating coupon "'+ _code +'"...');
        }
    </script>
@endsection
