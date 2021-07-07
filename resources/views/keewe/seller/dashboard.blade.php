@extends('keewe.layout.master')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/morris-js/morris.css') }}" type="text/css">
@endsection

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
                        <h5 class="title2">Painel de Visão Geral</h5>
                        <br>
                        <div class="row">
                            @foreach( $revenues as $entry )
                                @if( $entry->month == 'all' )
                                    <div class="col-6">
                                        <div class="card">
                                            <h5 class="card-header">Total de recebimentos</h5>
                                            <div class="card-body">
                                                <h5 class="display-4">{{ fmoney( $entry->revenue ) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col">
                                        <div class="card">
                                            <h5 class="card-header">Mês {{ $entry->month }}</h5>
                                            <div class="card-body">
                                                <p class="lead">{{ fmoney( $entry->revenue ) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="row mt-5">
                            <div class="col">
                                <h5 class="title2">Vendas</h5>
                                <br>
                                <div id="sales-chart"></div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col">
                                <h5 class="title2">Últimos 10 pedidos</h5>
                                <br>
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
                                        @foreach( $last_orders as $order )
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

                        <div class="row mt-5">
                            <div class="col">
                                <h5 class="title2">Perguntas</h5>
                                <br>
                                <div class="row">
                                    <div class="col text-left">
                                        Você possui <b>{{ $questions }} perguntas pendentes</b>
                                    </div>
                                    <div class="col text-right">
                                        <a href="{{ url('/perguntas') }}" class="btn btn-primary">
                                            {{ ($questions == 0) ? 'ver perguntas' : 'responder perguntas' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" id="store_id" value="{{ $store->id }}">
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('vendor/morris-js/morris.min.js') }}"></script>
    <script src="{{ asset('vendor/raphael.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            // Load Sales data
            var url = 'sales';
            var prs = { id: $('#store_id').val() };

            Common.get( url, prs, 'sales-chart', onChartDataLoaded );
        });

        function onChartDataLoaded( response )
        {
            if( response.success == false )
                return;

            buildChart( response.data );
        }

        function buildChart( _data )
        {
            console.log( _data );

            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'sales-chart',
                // Chart data records -- each entry in this array corresponds to a point on the chart
                data: _data,
                // The name of the data record attribute that contains x-values.
                xkey: 'month',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['revenue'],
                // Labels for the ykeys -- will be displayed when you hover over the chart
                labels: ['R$']
            });
        }


    </script>
@endsection
