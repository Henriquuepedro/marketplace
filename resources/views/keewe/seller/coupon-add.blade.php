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
                        <h5 class="title2">Novo cupom de desconto</h5>
                        <br>
                        <p>
                            Você pode criar cupons de descontos que podem ser usados pelos clientes durante a compra.
                            Os cupons são inseridos pelos clientes no carrinho no momento da compra e podem dar desconto
                            no frete, produtos ou total do pedido. Cupons são úteis para atrair novos clientes através de
                            ofertas ou campanhas de divulgação.
                        </p>
                        <p>
                            Depois de criar o cupom, informe seus clientes através de e-mail ou das redes sociais.
                        </p>
                        <p>
                            O cupom do tipo "Nenhum desconto" serve para casos em que você queira dar um brinde para
                            algum cliente específico após ele enviar o pedido, por exemplo, e não
                            necessariamente durante a compra.
                        </p>
                        <br><br>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <input type="hidden" name="id" value="{{ $coupon->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="code">Código do cupom <span class="required">*</span></label>
                                    <input id="code" name="code" type="text" class="form-control" value="{{ $coupon->code ?? null }}" style="text-transform: uppercase;">
                                    <small class="form-text text-muted">Ex.: PROMO10, NATAL2021, FRETEZERO</small>
                                </div>
                                <div class="form-group col-8">
                                    <label for="description">Descrição do cupom</label>
                                    <input id="description" name="description" type="text" class="form-control" value="{{ $coupon->description ?? null }}">
                                    <small class="form-text text-muted">Uma breve descrição do desconto que o cupon oferece</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="">Esse cupom poderá ser usado: <span class="required">*</span></label>
                                </div>
                                <div class="form-group col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="usage_limit" id="ul-one" value="one_per_customer">
                                        <label class="form-check-label" for="ul-one">Apenas 1 vez por cliente</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="usage_limit" id="ul-many" value="many_times">
                                        <label class="form-check-label" for="ul-many">Diversas vezes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="usage_limit" id="ul-lim" value="limited_times">
                                        <label class="form-check-label" for="ul-lim">Limitado</label>
                                    </div>
                                </div>
                                <div class="form-group col-3 limited-usage-times">
                                    <label for="limit">Limite <span class="required">*</span></label>
                                    <input id="limit" name="limit" type="text" class="form-control msk-int" value="{{ $coupon->limit ?? null }}">
                                    <small class="form-text text-muted">Digite o número máximo de vezes que o cupom pode ser utilizado</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-5">
                                    <label for="">Produtos que já estão em promoção: <span class="required">*</span></label>
                                </div>
                                <div class="form-group col-7">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="products_on_sale" id="posy" value="include">
                                        <label class="form-check-label" for="posy">São considerados no cálculo do desconto</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="products_on_sale" id="posn" value="not_include">
                                        <label class="form-check-label" for="posn">Não são considerados no cálculo do desconto</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-5">
                                    <label for="discount_type">Tipo de desconto <span class="required">*</span></label>
                                    <select id="discount_type" name="discount_type" class="form-control select2">
                                        <option value="0">Selecione</option>
                                        {!! options_data($cbo_types, null) !!}
                                    </select>
                                    <small class="form-text text-muted">Selecione a categoria</small>
                                </div>
                                <div class="form-group col-4 type_amount">
                                    <label for="discount_value">Valor do desconto <span class="required">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="pct-addon">R$</span>
                                        </div>
                                        <input id="" name="discount_value" type="text" class="form-control msk-dec" value="{{ $coupon->discount_value ?? null }}">
                                    </div>
                                    <small class="form-text text-muted">Valor, em Reais, para o cálculo do desconto</small>
                                </div>
                                <div class="form-group col-4 type_percent">
                                    <label for="discount_value">Valor do desconto <span class="required">*</span></label>
                                    <div class="input-group">
                                        <input id="" name="discount_value" type="text" class="form-control msk-dec" value="{{ $coupon->discount_value ?? null }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="pct-addon">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Valor percentual para o cálculo do desconto</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-5">
                                    <label for="min_order_value">Para pedidos à partir de </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="pct-addon">R$</span>
                                        </div>
                                        <input id="min_order_value" name="min_order_value" type="text" class="form-control msk-dec" value="{{ $coupon->min_order_value ?? null }}">
                                    </div>
                                    <small class="form-text text-muted">Opcional. Valor mínimo do pedido para aplicar desconto</small>
                                </div>
                                <div class="form-group col-7">
                                    <label for="">Incluir valor do frete para o cálculo? <span class="required">*</span></label>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="include_shipping" id="isy" value="yes">
                                        <label class="form-check-label" for="isy">Sim</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="include_shipping" id="isn" value="no">
                                        <label class="form-check-label" for="isn">Não</label>
                                    </div>
                                </div>
                                <div class="form-group col-8">

                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="">Produtos que aceitam esse desconto <span class="required">*</span></label>
                                </div>
                                <div class="form-group col-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="coupon_products" id="cpall" value="all">
                                        <label class="form-check-label" for="cpall">Todos os produtos</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="coupon_products" id="cplist" value="list">
                                        <label class="form-check-label" for="cplist">Produtos específicos</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row my-products" style="border: 1px solid #efefef; padding: 6px;">
                                @foreach( $products as $product )
                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="products[]" id="pdt{{ $product->id }}" value="{{ $product->id }}">
                                            <label class="form-check-label" for="pdt{{ $product->id }}">{{ $product->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-row">
                                <div class="form-group col">
                                    <p>&nbsp;</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        criar cupom
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

@section('scripts')
    @parent
    <script>
        $(document).ready(function(){
            // Hide elements
            $('.limited-usage-times').hide();
            $('.type_amount').hide();
            $('.type_percent').hide();
            //$('.my-products').hide();

            // Usage limit
            $('input[name=usage_limit]').off().on('change', function(){
                //
                //console.log( $(this).val() );

                if( $(this).val() == 'limited_times' )
                {
                    $('.limited-usage-times').show();
                }
                else
                {
                    $('.limited-usage-times').hide();
                }
            });

            // Discount type
            $('#discount_type').on('change', function(){
                //
                var type = $(this).val();

                //console.log( type );

                switch( type )
                {
                    case 'products_amount':
                    case 'total_amount':
                    case 'shipping_amount':
                        $('.type_amount').show();
                        $('.type_percent').hide();
                        break;
                    case 'products_percent':
                    case 'total_percent':
                    case 'shipping_pecent':
                        $('.type_amount').hide();
                        $('.type_percent').show();
                        break;
                }

                //$('.min_order').show();
            });

            // Products
            /*
            $('input[name=coupon_products]').off().on('change', function(){
                //
                //console.log( $(this).val() );

                if( $(this).val() == 'list' )
                {
                    $('.my-products').show();
                }
                else
                {
                    $('.my-products').hide();
                }
            });
            */

            // Check all checkbox
            $('#cpall').on('change', function(){
                //
                var checked = $(this).prop('checked');

                toogleProducts( checked );
            });
        });

        /**
         * Toogle all products checkboxes
         */
        function toogleProducts( _check )
        {
            $('.my-products').find('input').each(function(index, element){
                //
                $(element).attr('checked', _check);
            });
        }
    </script>
@endsection
