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
                        @if( $type === 'shipping' )
                            <div class="text-right">
                                <a href="{{ url('/addresses/create?type=shipping') }}" class="btn btn-sm btn-secondary">adicionar endereço de entrega</a>
                            </div>
                        @endif
                        <br>

                        @if( count($addresses) > 0 )
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Endereço</th>
                                        <th>Número</th>
                                        <th>Cidade</th>
                                        <th>Criado em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $addresses as $address )
                                        <tr>
                                            <td>{{ $address->id }}</td>
                                            <td>{{ $address->address }}</td>
                                            <td>{{ $address->number }}</td>
                                            <td>{{ $address->city }}</td>
                                            <td>{{ $address->simple_date }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Ações">
                                                    <a href="{{ url('/addresses/' . $address->id .'/edit') }}" type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Atualizar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <button type="button" onclick="delItem({{ $address->id }});" class="btn btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Excluir">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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
