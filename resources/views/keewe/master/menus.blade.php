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
                        <h5 class="title2">Lista de Menus</h5>
                        <div class="row">
                            <div class="col">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @if( count($menus) > 0 )
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Posição</th>
                                                <th>Ordem</th>
                                                <th>Criada em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $menus as $menu )
                                                <tr>
                                                    <td>{{ $menu->name }}</td>
                                                    <td>{{ $menu->position }}</td>
                                                    <td>{{ $menu->order }}</td>
                                                    <td>{{ dtf( $menu->created_at, 'd.m.Y' ) }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Ações">
                                                            <button type="button" class="btn btn-outline-success" onclick="Common.toUrl( '{{ url('/menus/' . $menu->id .'/edit') }}' );" data-toggle="tooltip" data-placement="bottom" title="Ver/Editar menu">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" onclick="Common.delete( '{{ url('/menus/' . $menu->id) }}', {} );" data-toggle="tooltip" data-placement="bottom" title="Excluir menu">
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
                                        Nenhum menu encontrado.
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
