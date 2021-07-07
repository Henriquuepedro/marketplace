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
                        <h5 class="title2">Colocar produtos em promoção</h5>
                        <br>
                        <p>
                            Use essa tela para colocar vários produtos em promoção de uma só vez. Siga os passos abaixo:
                        </p>
                        <ol>
                            <li>Selecione uma categoria de produtos</li>
                            <li>Se for necessário, desmarque os produtos que não entrarão em promoção</li>
                            <li>Informe o percentual de desconto e clique em "Calcular"</li>
                            <li>Faça os ajustes, se necessário, no campo "Preço promocional" de cada produto</li>
                            <li>Quando a edição dos preços estiver pronta, clique em "Salvar"</li>
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

                                @if( $category )
                                    <div class="form-group col-4">
                                        <label for="percent">Percentual de desconto <span class="required">*</span></label>
                                        <div class="input-group">
                                            <input id="percent" name="percent" type="text" class="form-control msk-dec" value="{{ $percent ? fdec($percent) : null }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="pct-addon">%</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Digite somente números</small>
                                    </div>

                                    <div class="form-group col-2">
                                        <label for="percent">&nbsp;</label>
                                        <br>
                                        <button id="btn-calc" type="button" class="btn btn-secondary">calcular</button>
                                    </div>
                                @endif
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
                                            <th>Preço normal</th>
                                            <th>Preço promocional</th>
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
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">R$</span>
                                                        </div>
                                                        <input id="price-{{ $item->id }}" name="prices[{{ $item->id }}]" type="text" class="form-control msk-dec" value="{{ fdec($item->new_price) }}">
                                                    </div>
                                                </td>
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
                                            salvar
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
                var _url = BASE_URL + '/promocao/create?category=' + $(this).val();

                document.location.href = _url;
            });

            // Calc button
            $('#btn-calc').off().on('click', function(event){
                //
                event.preventDefault();

                // Check percent
                var _pct = toFloat( $('#percent').val() );

                if( isNaN(_pct) )
                {
                    Common.notifyError('Digite um Percentual de desconto');
                    return false;
                }
                else if( _pct <= 0 )
                {
                    Common.notifyError('Digite um Percentual de desconto maior que 0 (zero)');
                    return false;
                }
                else if( _pct >= 100 )
                {
                    Common.notifyError('Digite um Percentual de desconto menor que 100 (cem)');
                    return false;
                }

                //console.log( _pct );

                // Reload page passing the Percent
                var _url = document.location.href + '&percent=' + _pct;

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

        /**
         * Returns a string parsed to float
         */
        function toFloat( _string )
        {
            _string = _string.replace('.', '');
            _string = _string.replace(',', '.');

            return parseFloat( _string );
        }
    </script>
@endsection
