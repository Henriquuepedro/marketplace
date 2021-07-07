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
                        <h5 class="title2">Relistar produtos</h5>
                        <br>
                        <p>
                            Use essa tela para relistar vários produtos de sua loja ao mesmo tempo.
                        </p>
                        <br><br>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">

                            @if( count($products) > 0 )
                                <div class="w-100"><p>&nbsp;</p></div>

                                <table id="products-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input id="check-all" name="check-all" type="checkbox" class="">
                                            </th>
                                            <th>Produto</th>
                                            <th>Nome</th>
                                            <th>Preço</th>
                                            <th>Novo estoque</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $products as $item )
                                            <tr>
                                                <td>
                                                    <input id="item-{{ $item->id }}" name="items[]" type="checkbox" class="" value="{{ $item->id }}">
                                                </td>
                                                <td style="width: 15%;">
                                                    <img class="thumb-table" src="{{ asset( $item->mainImageUrl() ) }}" title="" alt="">
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ fmoney($item->price) }}</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="qtd-{{ $item->id }}" name="qtd[{{ $item->id }}]" type="text" class="form-control msk-dec" value="{{ $item->quantity }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="form-row">
                                    <div class="form-group col">
                                        Nenhum produto encontrado
                                    </div>
                                </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group col">
                                    <p>&nbsp;</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        relistar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function(){
            // Check all checkbox
            $('#check-all').on('change', function(){
                //
                var checked = $(this).prop('checked');

                toogleCheckboxes( checked );
            });

            //
            if( $('#check-all').is(':visible') )
                $('#check-all').trigger('click');
        });

        /**
         * Toogle all products checkboxes
         */
        function toogleCheckboxes( _check )
        {
            $('#products-table tbody').find('tr').each(function(index, element){
                //
                var checkbox = $(element).find('td').eq(0).find('input[type=checkbox]').attr('checked', _check);
            });
        }
    </script>
@endsection
