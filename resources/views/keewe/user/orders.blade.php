@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h4>{{ $page_title }}</h4>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">

                        <!-- User menu -->
                        @include('keewe.user._nav')

                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <h5 class="title2">{{ $page_title }}</h5>
                        <br>

                        @if( count($orders) > 0 )
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Pedido</th>
                                        <th>Qtd de itens</th>
                                        <th>Valor total</th>
                                        <th>Frete</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $orders as $order )
                                        <tr>
                                            <td>{{ $order->tid }}</td>
                                            <td>{{ $order->countItems() }}</td>
                                            <td>{{ fmoney($order->amount) }}</td>
                                            <td>{{ fmoney($order->shipping) }}</td>
                                            <td>{{ dtf($order->created_at, 'd.m.Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Ações">
                                                    <a href="{{ url('/meus-pedidos/' . $order->id .'/edit') }}" type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Detalhes do pedido">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>
                                <big>Nenhum {{ $page_title }} encontrado.</big>
                                <br><br>
                                <a class="btn btn-secondary" href="{{ url('/addresses/create?type=billing') }}">Adicionar {{ $page_title }}</a>
                            </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        function delItem( item_id )
        {
            var url = '/addresses/' + item_id;

            Common.delete( url, {} );
        }
    </script>
@endsection
