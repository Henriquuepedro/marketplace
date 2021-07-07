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
                        <h5 class="title2">Lista de páginas</h5>
                        <div class="row">
                            <div class="col">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @if( count($pages) > 0 )
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Título da página</th>
                                                <th>Criada em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $pages as $page )
                                                <tr>
                                                    <td>{{ $page->title }}</td>
                                                    <td>{{ dtf( $page->created_at, 'd.m.Y' ) }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Ações">
                                                            <button type="button" class="btn btn-outline-success" onclick="Common.toUrl( '{{ url('/pages/' . $page->id .'/edit') }}' );" data-toggle="tooltip" data-placement="bottom" title="Ver/Editar página">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" onclick="Common.delete( '{{ url('/pages/' . $page->id) }}', {} );" data-toggle="tooltip" data-placement="bottom" title="Excluir loja">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col none">
                                        Nenhuma página encontrada.
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
