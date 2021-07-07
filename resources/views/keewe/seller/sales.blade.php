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
                        <h5 class="title2">Minhas Vendas</h5>

                        <!--
                        <form method="GET" action="{{ url('/pedidos') }}" accept-charset="utf-8">
                            <input type="hidden" name="page" value="{{ request()->page ?? 1 }}">

                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="q">Procurar</label>
                                    <input id="q" name="q" type="text" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="category">Categoria</label>
                                    <select id="category" name="category" palceholder="Categoria" class="form-control select2">
                                        <option value="">Todas</option>
                                        {!! cbo_categories( $categories ) !!}
                                    </select>
                                </div>
                                <div class="form-group col-md-3 text-right">
                                    <a href="{{ url('/produtos/create') }}" class="btn btn-primary">adicionar produto</a>
                                </div>
                            </div>
                        </form>
                        -->

                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Cliente</th>
                                            <th>Valor</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $orders as $order )
                                            <tr>
                                                <td>{{ $order->tid }}</td>
                                                <td>{{ $order->fullname }}</td>
                                                <td>{{ fmoney( $order->total_price ) }}</td>
                                                <td>{{ dtf( $order->created_at, 'd.m.Y' ) }}</td>
                                                <td>{{ __('orders.status.' . $order->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
