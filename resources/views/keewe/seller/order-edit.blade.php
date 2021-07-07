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
                        <h5 class="title2">Pedido #{{ $order->tid }}</h5>
                        <br>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $order->id ?? '' }}">
                            <dl class="row">
                                <dt class="col-4">Cliente</dt>
                                <dd class="col-8">{{ $order->client->fullname }}</dd>

                                <dt class="col-4">Endereço de entrega</dt>
                                <dd class="col-8">{!! address_from_order($json->shipping->address) !!}</dd>

                                <dt class="col-4">Método de pagamento</dt>
                                <dd class="col-8">{{ __('orders.pay_methods.' . $order->payment_method) }}</dd>

                                <dt class="col-4">Valor total</dt>
                                <dd class="col-8">{{ fmoney($total) }}</dd>

                                <!-- <dt class="col-4">Valor do frete</dt>
                                <dd class="col-8">{{-- fmoney($order->shipping) --}}</dd> -->

                                <dt class="col-4">N&ordm; de parcelas</dt>
                                <dd class="col-8">{{ $order->installments }}</dd>

                                <dt class="col-4">Data do pedido</dt>
                                <dd class="col-8">{{ dtf($order->created_at, 'd.M.Y') }}</dd>

                                <dt class="col-4">Status</dt>
                                <dd class="col-8">{{ __('orders.status.' . $order->status) }}</dd>

                                <dt class="col-4">Itens</dt>
                                <dd class="col-8">
                                    <ul class="list-unstyled">
                                        @foreach( $order->items as $item )
                                            @if( $item->product->store_id != $store->id )
                                                @continue
                                            @endif

                                            <li>{{ $item->quantity }} x {{ $item->name }} [<b>{{ fmoney($item->total_price) }}</b>]</li>

                                        @endforeach
                                    </ul>
                                </dd>

                                @if( ! is_null($order->tracking_code) )
                                    <dt class="col-4">Código de rastreamento</dt>
                                    <dd class="col-8">{{ $order->tracking_code }}</dd>
                                @endif
                            </dl>

                            <div class="form-row">
                                <div class="form-group col">
                                    <hr>
                                </div>
                            </div>

                            @if( $order->status === 'paid' )
                                <input type="hidden" name="status" value="in_transit">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <p>
                                            Esse <b>pedido</b> já está <b>pago</b>.
                                        </p>
                                        <p>
                                            Agora você deve <b>separar e embalar</b> o(s) produto(s).
                                        </p>
                                        <p>
                                            Depois, vá até o Correio, faça a <b>postagem do produto para o endereço de entrega</b>
                                            e peça pelo <b>código de rastreamento</b>.
                                        </p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-8">
                                        <label for="tracking_code">Código de rastreamento</label>
                                        <input id="tracking_code" name="tracking_code" type="text" class="form-control" value="{{ $product->name ?? '' }}">
                                        <small class="form-text text-muted">Digite o código de rastreamento fornecido pelos Correios</small>
                                    </div>
                                </div>
                            @elseif( $order->status === 'in_transit' )
                                <input type="hidden" name="status" value="delivered">
                                <div class="form-row">
                                    @php
                                    $opts = [
                                        [ 'id' => 'yes', 'value' => 'yes', 'text' => 'Sim' ],
                                        [ 'id' => 'no', 'value' => 'no', 'text' => 'Não' ]
                                    ];
                                    @endphp
                                    {!! radio_group('delivered', 'O produto já foi entregue?', $opts) !!}
                                </div>
                            @endif

                            @if( ($order->status === 'paid') || ($order->status === 'in_transit') )
                                <div class="form-row">
                                    <div class="form-group col-md-4 offset-md-4">
                                        <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                            atualizar pedido
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
