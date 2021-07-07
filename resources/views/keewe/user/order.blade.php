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
                        <h5 class="title2">Pedido #{{ $order->tid }}</h5>
                        <br>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $order->id ?? '' }}">
                            <dl class="row">
                                <dt class="col-3"><big>Status</big></dt>
                                <dd class="col-9"><big>{{ __('orders.status.' . $order->status) }}</big></dd>

                                <dt class="col-3">&nbsp;</dt>
                                <dd class="col-9">&nbsp;</dd>

                                <dt class="col-3">Endereço de entrega</dt>
                                <dd class="col-9">{!! address_from_order($json->shipping->address) !!}</dd>

                                <dt class="col-3">Método de pagamento</dt>
                                <dd class="col-9">{{ __('orders.pay_methods.' . $order->payment_method) }}</dd>

                                <dt class="col-3">Valor total</dt>
                                <dd class="col-9">{{ fmoney($total) }}</dd>

                                <!-- <dt class="col-3">Valor do frete</dt>
                                <dd class="col-9">{{-- fmoney($order->shipping) --}}</dd> -->

                                <dt class="col-3">N&ordm; de parcelas</dt>
                                <dd class="col-9">{{ $order->installments }}</dd>

                                <dt class="col-3">Data do pedido</dt>
                                <dd class="col-9">{{ dtf($order->created_at, 'd.m.Y') }}</dd>

                                <dt class="col-3">Itens:</dt>
                                <dd class="col-9"></dd>
                            </dl>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Qtd</th>
                                        <th>Produto</th>
                                        <th>Valor</th>
                                        <th>Loja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $order->items as $item )
                                    <tr>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ fmoney($item->total_price) }}</td>
                                        <td>
                                            <a href="{{ url('/lojas/' . $item->product->shop->slug) }}">{{ $item->product->shop->name }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="form-row">
                                <div class="form-group col">
                                    <hr>
                                </div>
                            </div>

                            @if( ! is_null($order->tracking_code) )
                                <dl class="row">
                                    <dt class="col-3">Código de rastreamento</dt>
                                    <dd class="col-9">{{ $order->tracking_code }}</dd>
                                </dl>
                            @endif

                            @if( ($order->status === 'delivered') && ! $occurrence )
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <p>
                                            O lojista informou que esse pedido já foi <b>entregue</b>.
                                        </p>
                                        <p>
                                            Se tiver algum problema com o(s) produto(s), utilize o campo abaixo
                                            para descrever a situação com o máximo de detalhes possível.
                                        </p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="reason">Motivo</label>
                                        <select id="reason" name="reason" class="form-control select2">
                                            <option value="">Selecione</option>
                                            <option value="replacement">Troca</option>
                                            <option value="return">Devolução</option>
                                            <option value="protest">Reclamação</option>
                                            <option value="compliment">Elogio</option>
                                        </select>
                                        <small class="form-text text-muted">Selecione o motivo do seu contato</small>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="product_id">Produto</label>
                                        <select id="product_id" name="product_id" class="form-control select2">
                                            <option value="">Selecione</option>
                                            @foreach( $order->items as $item )
                                                <option value="{{ $item->product_id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Selecione o produto</small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="description">Detalhes</label>
                                        <textarea id="description" name="description" class="form-control" rows="8"></textarea>
                                        <small class="form-text text-muted">Descreva os detalhes da sua solicitação</small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 offset-md-4">
                                        <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                            enviar solicitação
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if( $occurrence )
                                @php
                                    $item = $order->items()->where('product_id', '=', $occurrence->product_id)->first();
                                @endphp
                                <div class="form-row">
                                    <div class="form-group col">
                                        <h5>Sua solicitação:</h5>
                                    </div>
                                </div>
                                <dl class="row">
                                    <dt class="col-3">Produto</dt>
                                    <dd class="col-9"> {{ $item->name }} </dd>

                                    <dt class="col-3">Motivo</dt>
                                    <dd class="col-9"> {{ __('messages.occurrences.' . $occurrence->reason) }} </dd>

                                    <dt class="col-3">Detalhes</dt>
                                    <dd class="col-9"> {!! nl2br( $occurrence->description ) !!} </dd>
                                </dl>
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
