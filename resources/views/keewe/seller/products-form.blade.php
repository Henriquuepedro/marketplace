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
                        <h5 class="title2">Novo produto</h5>
                        <p>
                            Informe abaixo o nome de seu produto, uma descrição resumida e palavras-chave para buscadores,
                            como Google. Capriche na descrição completa de seu produto e selecione a(s) categoria(s) onde ele
                            deverá aparecer. <br>
                            Não esqueça de preencher as dimensões do pacote e seu peso, para o cálculo correto do valor de frete.
                            <br>
                            Campos marcados com [<span class="required">*</span>] são obrigatórios.
                            <br><br>
                        </p>
                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $product->id ?? '' }}">
                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="name">Nome do produto <span class="required">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{ $product->name ?? '' }}">
                                    <small class="form-text text-muted">Digite o nome do produto de forma bem clara</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="code">Código</label>
                                    <input id="code" name="code" type="text" class="form-control" value="{{ $product->code ?? '' }}">
                                    <small class="form-text text-muted">Pode ser números, letras ou ambos</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="meta_description">Descrição resumida para o Google</label>
                                    <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{ $product->meta_description ?? '' }}">
                                    <small class="form-text text-muted">Especifique claramente seu produto de forma resumida</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="meta_keywords">Palavras-chave para o Google</label>
                                    <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{ $product->meta_keywords ?? '' }}">
                                    <small class="form-text text-muted">Separe as palavras-chave com vírgula</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="description">Descrição completa <span class="required">*</span></label>
                                    <textarea id="description" name="description" class="form-control editor-sm">{{ $product->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <fieldset>
                                <legend>Imagens do produto <span class="required">*</span></legend>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <small class="form-text text-muted">
                                            Envie imagens nas dimensões 650 x 450, ou seja, 650 pixels de largura e 450 pixels de altura.
                                            <br>
                                            Dê preferência para imagens no formato .PNG ou .JPG
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <div class="dropzone"></div>

                                    </div>
                                    <!-- <x-dropzone-multi :images="$images" name="images[]" delete="true"></x-dropzone-multi> -->
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Categorias</legend>
                                <div class="form-row">
                                    <div class="form-group col cat-level-one">
                                        <label for="cbo-level-one">Categoria-pai <span class="required">*</span></label>
                                        <select id="cbo-level-one" name="categories[]" class="form-control select2">
                                            {!! cbo_categories_level_one($categories, $prod_cat ?? null) !!}
                                        </select>
                                    </div>
                                    <div class="form-group col cat-level-two">
                                        <label for="cbo-level-two">Categoria-filha <span class="required">*</span></label>
                                        <select id="cbo-level-two" name="categories[]" class="form-control select2">
                                        </select>
                                    </div>
                                    <div class="form-group col cat-level-three">
                                        <label for="cbo-level-three">Categoria <span class="required">*</span></label>
                                        <select id="cbo-level-three" name="categories[]" class="form-control select2">
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Opções</legend>
                                <div class="form-row">
                                    <!-- Featured -->
                                    @php
                                        $feat_opts = [
                                            [ 'id' => 'feat-yes', 'value' => 'yes', 'text' => 'Sim' ],
                                            [ 'id' => 'feat-no', 'value' => 'no', 'text' => 'Não' ]
                                        ];
                                    @endphp
                                    {!! radio_group('featured', 'Destaque na vitrine? <span class="required">*</span>', $feat_opts, $product->featured ?? null) !!}

                                    <!-- Free Shipping -->
                                    @php
                                        $fs_opts = [
                                            [ 'id' => 'fs-yes', 'value' => 'yes', 'text' => 'Sim' ],
                                            [ 'id' => 'fs-no', 'value' => 'no', 'text' => 'Não' ]
                                        ];
                                    @endphp
                                    {!! radio_group('free_shipping', 'Frete grátis? <span class="required">*</span>', $fs_opts, $product->free_shipping ?? null) !!}
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">&nbsp;</div>
                                    <div class="form-group col-6">No caso de Frete grátis, o lojista assume os custos de envio.</div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Selos</legend>
                                <div class="form-row">
                                    <!-- New -->
                                    @php
                                        $new_opts = [
                                            [ 'id' => 'new-yes', 'value' => 'yes', 'text' => 'Sim' ],
                                            [ 'id' => 'new-no', 'value' => 'no', 'text' => 'Não' ]
                                        ];
                                    @endphp
                                    {!! radio_group('new', 'Novidade <span class="required">*</span>', $new_opts, $product->new ?? null) !!}
                                </div>
                                <div class="form-row">
                                    <!-- Eco -->
                                    @php
                                        $eco_opts = [
                                            [ 'id' => 'eco-yes', 'value' => 'yes', 'text' => 'Sim' ],
                                            [ 'id' => 'eco-no', 'value' => 'no', 'text' => 'Não' ]
                                        ];
                                        $eco_lbl = 'Declaro que aceito os '
                                                 . '<a href="#" data-toggle="modal" data-target="#modal-eco">Termos de produto Eco-Friendly</a>';
                                    @endphp
                                    {!! radio_group('eco_friendly', 'Eco Friendly <span class="required">*</span>', $eco_opts, $product->eco_friendly ?? null) !!}

                                    {!! accept_checkbox('eco_friendly_accepted', $eco_lbl, $product->eco_friendly_accepted ?? null) !!}
                                </div>
                                <div class="form-row">

                                    <!-- Cruelty -->
                                    @php
                                        $veg_opts = [
                                            [ 'id' => 'cruel-yes', 'value' => 'yes', 'text' => 'Sim' ],
                                            [ 'id' => 'cruel-no', 'value' => 'no', 'text' => 'Não' ]
                                        ];
                                        $veg_lbl = 'Declaro que aceito os '
                                                 . '<a href="#" data-toggle="modal" data-target="#modal-veg">Termos de produto Vegano & Cruelty-Free</a>';
                                    @endphp
                                    {!! radio_group('cruelty_free', 'Vegano & Cruelty-Free <span class="required">*</span>', $veg_opts, $product->cruelty_free ?? null) !!}

                                    {!! accept_checkbox('cruelty_free_accepted', $veg_lbl, $product->cruelty_free_accepted ?? null) !!}
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Dimensões</legend>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="width">Largura <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <input id="width" name="width" type="text" class="form-control msk-int" value="{{ $product->width ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Largura mínima: 10,00 cm</small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="height">Altura <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <input id="height" name="height" type="text" class="form-control msk-int" value="{{ $product->height ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Altura mínima: 10,00 cm</small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="length">Comprimento <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <input id="length" name="length" type="text" class="form-control msk-int" value="{{ $product->length ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Comprimento mínimo: 15,00 cm</small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="weight">Peso <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <input id="weight" name="weight" type="text" class="form-control msk-int" value="{{ $product->weight ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">gramas</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Peso máximo: 30.000 g (30 kg)</small>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Estoque e preço</legend>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="quantity">Quantidade em estoque <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <input id="quantity" name="quantity" type="text" class="form-control msk-int" value="{{ $product->quantity ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">unidades</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="price">Preço unitário <span class="required">*</span></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input id="price" name="price" type="text" class="form-control msk-dec" value="{{ $product->price ?? '' }}">
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group col-md-4">
                                        <label for="old_price">Preço original</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input id="old_price" name="old_price" type="text" class="form-control msk-dec" value="{{ $product->old_price ?? '' }}">
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </fieldset>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                        {{ ($action === 'update' ? 'atualizar' : 'cadastrar') . ' produto' }}
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
<textarea id="existing-images" style="display: none;">{{ json_encode($images) }}</textarea>

<!-- Eco-Friendly & Cruelty Free modal -->
@include('keewe.partials.eco-friendly')
@include('keewe.partials.cruelty-free')

<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        var SelectedCategories;
        var UploadZone;

        // Disable auto discover for all elements:
        Dropzone.autoDiscover = false;

        $(document).ready(function(){
            // Hide stamp terms
            $('div.eco_friendly_accepted').hide();
            $('div.cruelty_free_accepted').hide();

            // Dropzone
            initDropzone('.dropzone');

            // Category select
            $('#cbo-level-one').on('select2:select', function(e){
                //
                var itemData = e.params.data;
                var route    = 'categories/' + itemData.id;

                Common.get( route, {}, '#cbo-level-two', onParentSelected );
            });

            // Sub-Category select
            $('#cbo-level-two').on('select2:select', function(e){
                //
                var itemData = e.params.data;
                var route    = 'categories/' + itemData.id;

                Common.get( route, {}, '#cbo-level-three', onParentSelected );
            });

            // Eco Friendly
            $('input[name=eco_friendly]').on('change', function(){
                //
                var _val = $(this).val();

                if( _val == 'yes' )
                    $('div.eco_friendly_accepted').show();
                else
                    $('div.eco_friendly_accepted').hide();
            });

            // Cruelty Free
            $('input[name=cruelty_free]').on('change', function(){
                //
                var _val = $(this).val();

                if( _val == 'yes' )
                    $('div.cruelty_free_accepted').show();
                else
                    $('div.cruelty_free_accepted').hide();
            });

            @if( $action == 'update' )
                loadSelected( '{!! implode(",", $prod_cat) !!}' );
            @endif

            @isset( $product )
                @if( $product->eco_friendly === 'yes' )
                    $('div.eco_friendly_accepted').show();
                @endif

                @if( $product->cruelty_free === 'yes' )
                    $('div.cruelty_free_accepted').show();
                @endif
            @endisset
        });

        /**
         * Handle parent selection in categories
         */
        function onParentSelected( result, target )
        {
            if( result.success !== true )
            {
                Common.responseHandler( result );
                return;
            }

            //console.log( result );

            if( ! result.categories )
                return;

            // Clear existing options on Level 2
            $(target).empty();
            $(target).append( new Option('Selecione', '', false, false) ).trigger('change');

            // Build data
            var nodes = result.categories;
            var item;

            //console.log( nodes );

            for( var i = 0; i < nodes.length; i++ )
            {
                item = new Option( nodes[i].name, nodes[i].id, false, false );

                //console.log( item );

                $(target).append( item ).trigger('change');
            }

            //
            if( target === '#cbo-level-two' )
                $('#cbo-level-three').empty().trigger('change');

            //
            if( SelectedCategories )
            {
                var idx = (target === '#cbo-level-two') ? 1 : 2;

                $(target).val( SelectedCategories[idx] ).trigger('change');

                if( idx === 2 )
                {
                    SelectedCategories = null;
                    return;
                }

                var route = 'categories/' + SelectedCategories[idx];

                Common.get( route, {}, '#cbo-level-three', onParentSelected );
            }
        }

        /**
         * Load selected categories
         */
        function loadSelected( selected )
        {
            if( (selected == null) || (selected == '') || (selected == 'undefined') )
                return;

            SelectedCategories = selected.split(',');

            // Get Level One
            var data  = $('#cbo-level-one').select2('data');
            var route = 'categories/' + data[0].id;

            Common.get( route, {}, '#cbo-level-two', onParentSelected );
        }

        // DROPZONE -------------------------------------------------------------------------------
        /**
         * Initializes DropZone
         */
        function initDropzone( element )
        {
            $(element).dropzone({
                url: BASE_URL + '/upload',
                params: {
                    _token: $('input[name=_token]').val(),
                    store_id: $('input[name=store_id]').val(),
                },
                uploadMultiple: true,
                paramName: 'upload',
                maxFiles: 5,
                maxFilesize: 2048,
                acceptedFiles: 'image/*',
                addRemoveLinks: false,
                // Translation
                dictDefaultMessage: 'Arraste e solte uma imagem aqui',
                dictFallbackMessage: 'Seu navegador não suport a função de arrastar-e-soltar',
                dictFallbackText: 'Utilize o campo abaixo pra enviar a imagem',
                dictFileTooBig: 'A imagem é muito grande (@{{filesize}}MiB). Tamanho máximo permitido: @{{maxFilesize}}MiB.',
                dictInvalidFileType: 'Você não pode enviar arquivos desse tipo',
                dictResponseError: 'Nosso servidor respondeu com o código @{{statusCode}}.',
                dictCancelUpload: 'Cancelar envio',
                dictUploadCanceled: 'Envio cancelado',
                dictCancelUploadConfirmation: 'Tem certeza de que deseja cancelar esse envio?',
                dictRemoveFile: 'Remover imagem',
                dictRemoveFileConfirmation: 'Tem certeza de que deseja remover essa imagem?',
                dictMaxFilesExceeded: 'Você não pode enviar mais arquivos',
                init: function(){
                    //
                    UploadZone = this;

                    var json_string = $('#existing-images').val();

                    if( json_string )
                    {
                        addExistingImages( this, json_string );
                    }

                    // Handler for success event
                    this.on('success', function(file, xhr){
                        //
                        //console.log( 'success', file );
                        //
                        var json = JSON.parse( file.xhr.response );
                        var $ipt = $('<input type="hidden" name="images[]" value="'+ json.id +'">');

                        // Add ID to file
                        file.id = json.id;

                        $(element).closest('.form-group').append( $ipt );
                    });

                    // Handler for added file event
                    this.on('addedfile', function(file){
                        //
                        console.log('addedfile', file);
                        //
                        makeDeleteLink( UploadZone, file );
                    });

                    // Remove File when max files exceeded
                    this.on("maxfilesexceeded", function(file) {
                        //
                        console.log('maxfilesexceeded', file);
                        //
                        UploadZone.removeFile(file);
                    });
                }
            });
        }

        /**
         * Creates delete link for images
         */
        function makeDeleteLink( $dz, file )
        {
            // Create the remove link
            var del_link;

            if( file.id )
            {
                del_link = Dropzone.createElement('<a class="dz-remove" href="#" rel="'+ file.id +'" data-dz-remove="">Remover imagem</a>');
            }
            else
            {
                del_link = Dropzone.createElement('<a class="dz-remove" href="#" data-dz-remove="">Remover imagem</a>');
            }

            del_link.addEventListener('click', function(e){
                //
                console.log('dz-remove.click');
                //
                e.preventDefault();
                e.stopPropagation();

                removeFile( file.id );

                $dz.removeFile(file);
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild( del_link );
        }

        /**
         * Add existing image to DropZone
         */
        function addExistingImages( $dz, json_data )
        {
            var _name, _size, _url, _img, _id;
            var _ipt;
            var json = JSON.parse( json_data );

            for( var i = 0; i < json.length; i++ )
            {
                _name = json[i].original_name;
                _size = json[i].size;
                _id   = json[i].id;
                _url  = BASE_URL + json[i].url;
                _img  = { name: _name, size: _size, imageUrl: _url, id: _id };

                // Push file to collection
                $dz.files.push( _img );
                // Emulate event to create interface
                $dz.emit('addedfile', _img);
                // Add thumbnail url
                $dz.emit('thumbnail', _img, _url);
                // Add status processing to file
                //this.emit('processing', _img);
                // Add status success to file AND RUN EVENT success from responce
                //this.emit('success', _img, response, false);
                // Add status complete to file
                $dz.emit('complete', _img);

                _ipt = $('<input type="hidden" name="images[]" value="'+ json[i].id +'">');
                $('.dropzone').closest('.form-group').append( _ipt );

                makeDeleteLink( $dz, _img );
            }
        }

        /**
         * Removes an image
         */
        function removeFile( fileid )
        {
            //
            console.log('removeFile('+ fileid +')');
            //

            // Check for given file ID
            if( ! fileid )
                return;

            var inputs = $('.dropzone').closest('.form-group').find('input[type=hidden]');
            var $input;

            for( var j = inputs.length - 1; j >= 0; j-- )
            {
                $input = $(inputs[j]);

                if( $input.val() == fileid )
                {
                    $input.remove();
                    break;
                }
            }

            var url = BASE_URL + '/upload/'+ fileid +'/delete';
            var data = { referer: document.location.href };

            Common.post( url, data, null, onFileRemoved );
        }

        /**
         * Callback for image removed
         */
        function onFileRemoved( json )
        {
            //
            console.log('onFileRemoved('+ json +')');
            //
        }
    </script>
@endsection
