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
                        @include('keewe.master._nav')

                    </div>
                    <div class="col-md-9">
                        <h5 class="title2">
                            Lista de lojas {{ ( request()->status === 'inactive' ) ? 'bloqueadas' : '' }}
                        </h5>
                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @if( count($stores) > 0 )
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome da loja</th>
                                                <th>Lojista</th>
                                                <th>Produtos</th>
                                                <th>Criada em</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $stores as $store )
                                                <tr>
                                                    <td>{{ $store->name }}</td>
                                                    <td>{{ $store->owner->fullname }}</td>
                                                    <td>{{ $store->products_count }}</td>
                                                    <td>{{ dtf( $store->created_at, 'd.m.Y' ) }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Ações">
                                                            <button type="button" class="btn btn-outline-success" onclick="Common.toUrl( '{{ url('/stores/' . $store->id) }}' );" data-toggle="tooltip" data-placement="bottom" title="Ver loja">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            @if( $store->status === 'inactive' )
                                                                <button type="button" class="btn btn-outline-warning" onclick="Common.post( '{{ url('/stores/status') }}', {id: {{ $store->id }}, action: 'unblock'}, null, Common.deleteHandler );" data-toggle="tooltip" data-placement="bottom" title="Desbloquear loja">
                                                                    <i class="fa fa-unlock"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-outline-warning" onclick="Common.post( '{{ url('/stores/status') }}', {id: {{ $store->id }}, action: 'block'}, null, Common.deleteHandler );" data-toggle="tooltip" data-placement="bottom" title="Bloquear loja">
                                                                    <i class="fa fa-lock"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn btn-outline-danger" onclick="Common.delete( '{{ url('/stores/' . $store->id) }}', {} );" data-toggle="tooltip" data-placement="bottom" title="Excluir loja">
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
                                        Nenhuma loja encontrada.
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
