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
                        <h5 class="title2"> Lista de cupons de desconto </h5>
                        <br>
                        <div class="row">
                            <div class="col">
                                @if( count($coupons) > 0 )
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Disponíveis</th>
                                                <th>Utilizados</th>
                                                <th>Desconto</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $coupons as $coupon )
                                                <tr>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>{{ ! is_null($coupon->limit) ? $coupon->limit : '---' }}</td>
                                                    <td>{{ '0' }}</td>
                                                    <td>{{ coupon_info($coupon) }}</td>
                                                    <td>{{ __('messages.' . $coupon->status) }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Ações">
                                                            <button type="button" class="btn btn-outline-success" onclick="Common.toUrl( '{{ url('/cupons/'. $coupon->id .'/edit') }}' );" data-toggle="tooltip" data-placement="bottom" title="Ver/Editar cupom">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" onclick="Common.delete( '{{ url('/cupons/' . $coupon->id) }}', {} );" data-toggle="tooltip" data-placement="bottom" title="Excluir cupom">
                                                                <i class="fa fa-ban"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col none">
                                        Nenhuma variação encontrada.
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@csrf
<!-- Content Section End -->
@endsection
