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
                        <h5 class="title2">Detalhes do cupom de desconto</h5>
                        <br>
                        <dl class="row">
                            <dt class="col-4">Código do cupom:</dt>
                            <dd class="col-6">{{ $coupon->code }}</dd>

                            <dt class="col-4">Descrição do cupom:</dt>
                            <dd class="col-6">{{ $coupon->description }}</dd>

                            <dt class="col-4">Limite de uso:</dt>
                            <dd class="col-6">{{ __('messages.usage_limit.' . $coupon->usage_limit) }}</dd>

                            <dt class="col-4">Cupons disponíveis:</dt>
                            <dd class="col-6">{{ $coupon->limit }}</dd>

                            <dt class="col-4">Cupons utilizados:</dt>
                            <dd class="col-6">{{ '0' }}</dd>

                            <dt class="col-4">Produtos em promoção:</dt>
                            <dd class="col-6">{{ __('messages.products_on_sale.' . $coupon->products_on_sale) }}</dd>

                            <dt class="col-4">Desconto:</dt>
                            <dd class="col-6">{{ coupon_info($coupon) }}</dd>

                            <dt class="col-4">Criado em:</dt>
                            <dd class="col-6">{{ dtf($coupon->created_at, 'd.m.Y') }}</dd>

                            <dt class="col-4">Status:</dt>
                            <dd class="col-6">{{ __('messages.' . $coupon->status) }}</dd>

                            <dt class="col-4">Produtos:</dt>
                            <dd class="col-6">
                                <ul class="list-unstyled">
                                    @foreach( $coupon->products() as $item )
                                        <li>
                                            <i class="fa fa-fw fa-check"></i> {{ $item->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        </dl>

                        <br>
                        <h5 class="title3">Pedidos que usaram esse cupom</h5>
                        <p>
                            Esse cupom ainda não foi usado em nenhum pedido. Quando os clientes fizerem compras
                            usando esse cupom, a lista de pedidos aparecerá aqui e você poderá saber
                            exatamente qual foi o retorno gerado pelo cupom.
                        </p>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $coupon->id ?? '' }}">
                            <input type="hidden" name="status" value="{{ (($coupon->status == 'active') ? 'inactive' : 'active') }}">
                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        {{ (($coupon->status == 'active') ? 'desativar cupom' : 'ativar cupom') }}
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
