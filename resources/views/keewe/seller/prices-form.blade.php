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
                        <h5 class="title2">Reajuste de Preços</h5>
                        <br>
                        <p>
                            Use essa tela para mudar o preço de vários produtos simultaneamente.
                            Você pode aplicar um reajuste percentual, incremental ou definir um mesmo preço
                            para todos os produtos selecionados.
                        </p>
                        <p>
                            Note que essa tela não coloca nem altera os preços promocionais nos produtos.
                            Caso queira colocar preços promocionais, utilize o link
                            <a href="{{ url('/promocao?act=add') }}">Colocar em promoção</a>.
                        </p>
                        <ol>
                            <li>Selecione uma categoria de produtos</li>
                            <li>Se for necessário, desmarque os produtos que não serão reajustados</li>
                            <li>Informe o percentual de ajuste ou o valor fixo e clique em "Calcular"</li>
                            <li>Faça os ajustes, se necessário, no campo "Novo preço" de cada produto</li>
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
                            </div>

                            @if( $category )
                                <div class="form-row">
                                    <div class="form-group col">
                                        <div class="card-deck">

                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Ajuste percentual</h5>
                                                    <p class="card-text">
                                                        Aplique um ajuste percentual nos produtos selecionados
                                                    </p>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="pct-dir-up" name="pct_dir" value="increase" {{ ($op == 'increase') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pct-dir-up">Aumentar</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="pct-dir-dw" name="pct_dir" value="decrease" {{ ($op == 'decrease') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pct-dir-dw">Diminuir</label>
                                                    </div>
                                                    <div class="input-group mt-4 mb-3">
                                                        <input type="text" class="form-control msk-dec" id="pct" name="pct" value="{{ ! empty($pct) ? fdec($pct) : null }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">% do total</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="button" id="calc-pct" class="btn btn-secondary btn-block">calcular</button>
                                                </div>
                                            </div>

                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Ajuste incremental</h5>
                                                    <p class="card-text">
                                                        Incremente ou decremente um valor fixo
                                                    </p>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="val-dir-up" name="val_dir" value="increase" {{ ($op == 'increase') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="val-dir-up">Aumentar</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="val-dir-dw" name="val_dir" value="decrease" {{ ($op == 'decrease') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="val-dir-dw">Diminuir</label>
                                                    </div>
                                                    <div class="input-group mt-4 mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">R$</span>
                                                        </div>
                                                        <input type="text" class="form-control msk-dec" id="val" name="val" value="{{ ! empty($val) ? fdec($val) : null }}">
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="button" id="calc-val" class="btn btn-secondary btn-block">calcular</button>
                                                </div>
                                            </div>

                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5 class="card-title">Preço fixo</h5>
                                                    <p class="card-text">
                                                        Ou coloque o mesmo preço nos produtos selecionados
                                                    </p>
                                                    <div class="input-group mt-4 mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Preço (R$)</span>
                                                        </div>
                                                        <input type="text" class="form-control msk-dec" id="fix" name="fix" value="{{ ! empty($fix) ? fdec($fix) : null }}">
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="button" id="calc-fix" class="btn btn-secondary btn-block">calcular</button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <p>&nbsp;</p>
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
                                            <th>Preço normal</th>
                                            <th>Novo preço</th>
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
                var _url = BASE_URL + '/precos/create?category=' + $(this).val();

                document.location.href = _url;
            });

            // Percent calc button --------------------------------------------
            $('#calc-pct').off().on('click', function(event){
                //
                event.preventDefault();

                var _pct = toFloat( $('#pct').val() );

                if( checkPercentage(_pct) == false )
                    return false;

                // Get operation
                var _op = getPercentageOperation();

                if( _op == false )
                    return false;

                // Reload page passing the Percent
                Common.toUrl( getUrl() + '&pct=' + _pct + '&op='+ _op );
            });

            // Value calc button ----------------------------------------------
            $('#calc-val').off().on('click', function(event){
                //
                event.preventDefault();

                var _val = toFloat( $('#val').val() );

                if( checkValue(_val) == false )
                    return false;

                // Get operation
                var _op = getValueOperation();

                if( _op == false )
                    return false;

                // Reload page passing the Value
                Common.toUrl( getUrl() + '&val=' + _val + '&op='+ _op );
            });

            // Fixed calc button ----------------------------------------------
            $('#calc-fix').off().on('click', function(event){
                //
                event.preventDefault();

                var _fix = toFloat( $('#fix').val() );

                if( checkFixed(_fix) == false )
                    return false;

                // Reload page passing the Fixed
                Common.toUrl( getUrl() + '&fix=' + _fix );
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

        /**
         * Verify the percent value
         */
        function checkPercentage( _pct )
        {
            if( isNaN(_pct) )
            {
                Common.notifyError('Digite um Percentual de reajuste');
                return false;
            }
            else if( _pct <= 0 )
            {
                Common.notifyError('Digite um Percentual de reajuste maior que 0 (zero)');
                return false;
            }
            else if( _pct >= 100 )
            {
                Common.notifyError('Digite um Percentual de reajuste menor que 100 (cem)');
                return false;
            }
        }

        /**
         * Verify the percentage operation
         */
        function getPercentageOperation()
        {
            var pct_up = $('#pct-dir-up').prop('checked');
            var pct_dw = $('#pct-dir-dw').prop('checked');

            //console.log( pct_up, pct_dw );

            if( pct_up == true )
                return 'increase';
            else if( pct_dw == true )
                return 'decrease';
            else
            {
                Common.notifyError('Selecione uma opção para Aumentar ou Diminuir o preço');
                return false;
            }
        }

        /**
         * Verify the value
         */
        function checkValue( _val )
        {
            if( isNaN(_val) )
            {
                Common.notifyError('Digite um Valor para o reajuste');
                return false;
            }
            else if( _val <= 0 )
            {
                Common.notifyError('Digite um Valor de reajuste maior que 0 (zero)');
                return false;
            }
        }

        /**
         * Verify the value operation
         */
        function getValueOperation()
        {
            var val_up = $('#val-dir-up').prop('checked');
            var val_dw = $('#val-dir-dw').prop('checked');

            //console.log( val_up, val_dw );

            if( val_up == true )
                return 'increase';
            else if( val_dw == true )
                return 'decrease';
            else
            {
                Common.notifyError('Selecione uma opção para Aumentar ou Diminuir o preço');
                return false;
            }
        }

        /**
         * Verify the fixed value
         */
        function checkFixed( _fix )
        {
            if( isNaN(_fix) )
            {
                Common.notifyError('Digite um Valor fixo para o reajuste');
                return false;
            }
            else if( _fix <= 0 )
            {
                Common.notifyError('Digite um Valor fixo maior que 0 (zero)');
                return false;
            }
        }

        function getUrl()
        {
            return BASE_URL + '/precos/create?category=' + $('#category').val();
        }
    </script>
@endsection
