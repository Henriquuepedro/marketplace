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
                        <h5 class="title2">Esgotar produtos</h5>
                        <br>
                        <p>
                            Use essa tela para esgotar vários produtos de sua loja ao mesmo tempo.
                        </p>
                        <ol>
                            <li>Selecione uma categoria de produtos</li>
                            <li>Selecione os produtos que deseja esgotar</li>
                            <li>Clique em "Esgotar"</li>
                        </ol>
                        <br><br>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="category">Categoria <span class="required">*</span></label>
                                    <select id="category" name="category" class="form-control select2">
                                        <option value="0">Selecione</option>
                                        {!! cbo_categories_level_one($categories, $category ?? null) !!}
                                    </select>
                                    <small class="form-text text-muted">Selecione a categoria</small>
                                </div>
                            </div>

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
                                            <th>Estoque atual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $products as $item )
                                            <tr>
                                                <td>
                                                    <input id="item-{{ $item->id }}" name="items[]" type="checkbox" class="" value="{{ $item->id }}">
                                                </td>
                                                <td style="width: 15%;">
                                                    <img class="thumb-table" src="{{ asset( $item->image ) }}" title="" alt="">
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ fmoney($item->price) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="form-row">
                                    <div class="form-group col">
                                        Nenhum produto encontrado nessa categoria
                                    </div>
                                </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group col">
                                    <p>&nbsp;</p>
                                </div>
                            </div>

                            @if( $category )
                                <div class="form-row">
                                    <div class="form-group col-md-4 offset-md-4">
                                        <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                            esgotar
                                        </button>
                                    </div>
                                </div>
                            @endif
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
            // Category select
            $('#category').on('change', function(){
                //
                //console.log( $(this).val() );

                // Reload page passing the Category
                var _url = BASE_URL + '/estoque/0/edit?category=' + $(this).val();

                document.location.href = _url;
            });

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
