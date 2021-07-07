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
                        <h5 class="title2">
                            {{ ($action === 'update' ? 'Atualizar' : 'Nova') . ' Variação de produto' }}
                        </h5>
                        <br>
                        <p>
                            Tabelas de variações servem para definir variações de um mesmo produto, como variações de tamanho,
                            cor, acabamento, etc. Por exemplo, você pode cadastrar um produto que é uma camiseta e essa
                            camiseta pode ter duas variações: cor e tamanho.
                        </p>
                        <p>
                            Para cada variação você precisa definir uma lista de opções. Por exemplo, para a variação "cor"
                            você pode definir as opções "branco", "azul", "vermelho", etc. As opções são textos livres e
                            você pode definí-las como achar melhor.
                        </p>
                        <p>Cada tabela de variação é criada especificamente para um produto.</p>
                        <p>
                            Campos marcados com [<span class="required">*</span>] são obrigatórios.
                            <br><br>
                        </p>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $variation->id ?? '' }}">
                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="product_id">Produto <span class="required">*</span></label>
                                    <select id="product_id" name="product_id" class="form-control select2">
                                        {!! options_data( $products, $variation->product_id ?? null ) !!}
                                    </select>
                                    <small class="form-text text-muted">Selecione o produto</small>
                                </div>

                                <div class="form-group col-6">
                                    <label for="name">Nome da variação</label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{ $variation->name ?? '' }}">
                                    <small class="form-text text-muted">Exemplo: Cor ou Tamanho</small>
                                </div>
                            </div>

                            @if( isset($variation) )
                                <div class="w-100"><p>&nbsp;</p></div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <h5 class="title2">Opções</h5>
                                    </div>
                                </div>

                                <div class="form-row options-head">
                                    <div class="form-group col-2">N&ordm;</div>
                                    <div class="form-group col-4">Nome da opção</div>
                                    <div class="form-group col-3">Quantidade <small class="text-muted">(opcional)</small></div>
                                    <!-- <div class="form-group col-3">Preço <small class="text-muted">(opcional)</small></div> -->
                                </div>

                                <div class="form-row options-row first">
                                    <div class="form-group col-2">
                                        <input name="opt_position" type="number"  class="form-control" value="{{ count($variation->options) + 1 }}">
                                    </div>
                                    <div class="form-group col-4">
                                        <input name="opt_name" type="text" class="form-control" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <input name="opt_quantity" type="text" class="form-control msk-int" value="">
                                    </div>
                                    <!--
                                    <div class="form-group col-3">
                                        <input name="opt_price" type="text" class="form-control msk-dec" value="">
                                    </div>
                                    -->

                                    <div class="form-group col-2 text-right">
                                        <div class="btn-group" role="group" aria-label="Ações">
                                            <button id="add-btn" type="button" class="btn btn-outline-success" onclick="addRow(this);" title="Adicionar opção">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @foreach( $variation->options as $option )
                                    <div class="form-row options-row">
                                        <input type="hidden" name="opt_id" value="{{ $option->id }}">
                                        <div class="form-group col-2">
                                            <input name="opt_position" type="number" class="form-control" value="{{ $option->position }}">
                                        </div>
                                        <div class="form-group col-4">
                                            <input name="opt_name" type="text" class="form-control" value="{{ $option->name }}">
                                        </div>
                                        <div class="form-group col-3">
                                            <input name="opt_quantity" type="text" class="form-control msk-int" value="{{ $option->quantity }}">
                                        </div>
                                        <!--
                                        <div class="form-group col-3">
                                            <input name="opt_price" type="text" class="form-control msk-dec" value="{{ $option->price }}">
                                        </div>
                                        -->

                                        <div class="form-group col-2 text-right">
                                            <div class="btn-group" role="group" aria-label="Ações">
                                                <button type="button" class="btn btn-outline-info" onclick="updateRow(this);" title="Atualizar opção">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" onclick="delRow(this);" title="Excluir opção">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div class="form-row">
                                <div class="form-group col">
                                    <hr>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        {{ ($action === 'update' ? 'atualizar' : 'cadastrar') . ' variação do produto' }}
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
            //
        });

        /**
         * Add a new Option
         */
        function addRow( _sender )
        {
            // Get data
            var $row = $(_sender).closest('div.options-row');
            var data = {
                variation_id: $('input[name=id]').val(),
                name: $row.find('input[name=opt_name]').val(),
                quantity: $row.find('input[name=opt_quantity]').val(),
                price: $row.find('input[name=opt_price]').val(),
                position: $row.find('input[name=opt_position]').val(),
            };

            // URL
            var url = BASE_URL + '/var-options';

            // Make the Post
            Common.post( url, data, '#add-btn', Common.saveHandler );
        }

        /**
         * Updates an Option
         */
        function updateRow( _sender )
        {
            // Get data
            var $row = $(_sender).closest('div.options-row');
            var _id  = $row.find('input[name=opt_id]').val()
            var data = {
                id: _id,
                variation_id: $('input[name=id]').val(),
                name: $row.find('input[name=opt_name]').val(),
                quantity: $row.find('input[name=opt_quantity]').val(),
                price: $row.find('input[name=opt_price]').val(),
                position: $row.find('input[name=opt_position]').val(),
                _method: 'PUT',
            };

            // URL
            var url = BASE_URL + '/var-options/' + _id;

            // Make the Post
            Common.post( url, data, _sender, Common.saveHandler );
        }

        /**
         * Deletes an Option
         */
        function delRow( _sender )
        {
            var $row = $(_sender).closest('div.options-row');
            var data = {
                variation_id: $('input[name=id]').val(),
                id: $row.find('input[name=opt_id]').val(),
                _method: 'DELETE',
            };

            // URL
            var url = BASE_URL + '/var-options/' + data.id;

            // Make the Post
            Common.post( url, data, _sender, Common.saveHandler );
        }
    </script>
@endsection
