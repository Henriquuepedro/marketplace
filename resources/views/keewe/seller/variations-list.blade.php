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
                        <h5 class="title2"> Variações de Produtos </h5>
                        <div class="text-right">
                            <a href="{{ url('/variacoes/create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> adicionar variação
                            </a>
                        </div>
                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @if( count($variations) > 0 )
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Nome da variação</th>
                                                <th>Criada em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $variations as $variation )
                                                <tr>
                                                    <td>{{ $variation->product->name }}</td>
                                                    <td>{{ $variation->name }}</td>
                                                    <td>{{ dtf( $store->created_at, 'd.m.Y' ) }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Ações">
                                                            <button type="button" class="btn btn-outline-success" onclick="Common.toUrl( '{{ url('/variacoes/'. $variation->id .'/edit') }}' );" data-toggle="tooltip" data-placement="bottom" title="Ver/Editar variação">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" onclick="Common.delete( '{{ url('/variacoes/' . $variation->id) }}', {} );" data-toggle="tooltip" data-placement="bottom" title="Excluir variação">
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
