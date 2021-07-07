@extends('keewe.layout.master')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/jstree/dist/themes/default/style.min.css') }}" type="text/css">
@endsection

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
                        <h5 class="title2">
                            Itens do Menu
                            @if( isset($menu_name) )
                                <em class="text-black-50">{{ $menu_name }}</em>
                            @endif
                        </h5>
                        <p>&nbsp;</p>

                        <div class="row">
                            <div class="col-6">
                                <label for="menu_id">Selecione o Menu</label>
                                <select id="menu_id" palceholder="Menu" class="form-control select2">
                                    <option value="">Selecione</option>
                                    {!! options( 'App\Models\Common\Menu', 'name', 'id', 'name', $menu_id ) !!}
                                </select>
                            </div>
                        </div>
                        <p>&nbsp;</p>

                        <div class="row">
                            <div class="col-5">
                                <div id="js-tree"></div>
                            </div>
                            <div class="col-7">
                                <button id="btnadd" type="button" class="btn btn-secondary" onclick="addNewItem();">adicionar item de menu</button>
                                <form id="fritem" method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                                    @csrf
                                    <input type="hidden" name="_method" value="">
                                    <input type="hidden" name="id" value="">
                                    <input type="hidden" name="menu_id" value="{{ $menu_id }}">
                                    <input id="parent_id" type="hidden" name="parent_id" value="">
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="name">Nome do Item</label>
                                            <input id="name" name="name" type="text" class="form-control" value="">
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="target">Alvo</label>
                                            <select id="target" name="target" palceholder="Alvo" class="form-control select2">
                                                <option value="">Selecione</option>
                                                {!! options_data( $target_options ) !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col tgt-page">
                                            <label for="page_id">Página</label>
                                            <select id="page_id" name="page_id" palceholder="Página" class="form-control select2">
                                                <option value="">Selecione</option>
                                                {!! options( 'App\Models\Common\Page', 'title', 'id', 'title' ) !!}
                                            </select>
                                        </div>
                                        <div class="form-group col tgt-url">
                                            <label for="url">URL</label>
                                            <input id="url" name="url" type="text" class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4 offset-md-4">
                                            <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">
                                                gravar
                                            </button>
                                        </div>
                                    </div>
                                </form>
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

@section('scripts')
    @parent
    <script src="{{ asset('vendor/jstree/dist/jstree.min.js') }}"></script>
    <script>
        // Global vars
        var ItemsContainer = null;
        var Items = null;
        var MenuID = null;

        $(document).ready(function(){
            // Get vars
            ItemsContainer = '#js-tree';
            Items = '{!! $items ?? null !!}';
            MenuID = '{{ $menu_id ?? null }}';

            //console.log( ItemsContainer );
            //console.log( Items );
            //console.log( MenuID );

            // Set visibilities
            $('.tgt-page').hide();
            $('.tgt-url').hide();
            $('#fritem').hide();

            // Stop if empty Items
            if( Items != '' )
            {
                setTimeout(function(){
                    setupTree();
                }, 400);
            }

            // Selects
            setTimeout(function(){
                setupSelects();
            }, 800);
        });

        /**
         * Setups selects
         */
        function setupSelects()
        {
            // Select2 change: Menu ID ----------------------------------------
            $('#menu_id').on('select2:select', function(e){
                // Get data
                var data = e.params.data;

                if( data.id === 'Selecione' )
                    return;

                if( parseInt(data.id) == parseInt(MenuID) )
                    return;

                Common.navTo( '/menus-items?menu_id=' + data.id );
            });

            if( MenuID )
            {
                $('#menu_id').val( MenuID ).trigger('change');
            }

            // Select2 change: Target -----------------------------------------
            $('#target').on('select2:select', function(e){
                // Get data
                var data = e.params.data;

                setFieldsDisplay( data.id );
            });
        }

        /**
         * Sets form fields visibility
         */
        function setFieldsDisplay( _target )
        {
            switch( _target )
                {
                    case 'page':
                        $('.tgt-page').show();
                        $('.tgt-url').hide();
                        break;
                    case 'url':
                    case 'external':
                        $('.tgt-page').hide();
                        $('.tgt-url').show();
                        break;
                }
        }

        function addNewItem()
        {
            $('#btnadd').hide();
            $('#fritem').show();
        }

        /**
         * Setups jsTree component
         */
        function setupTree()
        {
            // Init jsTree ----------------------------------------------------
            $(ItemsContainer).jstree({
                core: {
                    data: JSON.parse( Items ),
                    check_callback: true,
                    multiple: false,
                    themes : {
                        variant: 'large'
                    }
                },
                plugins: [ 'contextmenu' ],
                contextmenu: {
                    items: function(){
                        //
                        return contextMenuItems();
                    }
                }
            });

            // Move Node event handler ----------------------------------------
            /*
            $(ItemsContainer).on('move_node.jstree', function( node ){
                // Get a reference to instance
                var opts = { no_state: true, no_li_attr: true, no_a_attr: true };
                var json = $.jstree.reference(ItemsContainer).get_json(null, opts);

                //console.log( json );

                var url = BASE_URL + '/menus-items/rebuild';
                var params = {
                    _token: $('input[name=_token]').val(),
                    menu_id: $('input[name=menu_id]').val(),
                    items: json
                };

                Common.post( url, params, null, Common.saveHandler );
            });
            */
        }

        /**
         * Creates and setup context menu items
         */
        function contextMenuItems()
        {
            return {
                edit: {
                    separator_before: false,
                    separator_after: false,
                    label: 'Editar',
                    action: function( data ){
                        //
                        var inst = $.jstree.reference(data.reference);
                        var obj  = inst.get_node(data.reference);
                        var url  = 'menus-items/'+ obj.id;

                        Common.get( url, null, 'form', onItemEdit );
                    }
                },
                remove: {
                    separator_before: false,
                    separator_after: false,
                    label: 'Remover',
                    action: function( data ){
                        //
                        var inst = $.jstree.reference(data.reference);
                        var obj  = inst.get_node(data.reference);
                        var _url = '/menus-items/'+ obj.id;

                        var formData = new FormData();

                        formData.append( 'menu_id', $('input[name=menu_id]').val() );
                        formData.append( '_token', $('input[name=_token]').val() );
                        formData.append( '_method', 'DELETE' );

                        // Make the delete request
                        $.ajax({
                            url: BASE_URL + _url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            complete: function( jqXHR, textStatus ){
                                // Call response handler
                                Common.deleteHandler( jqXHR.responseJSON );
                            }
                        });
                    }
                }
            };
        }

        /**
         * Handler for Menu Item edit action
         */
        function onItemEdit( response )
        {
            //console.log( response );
            Common.responseHandler( response );

            if( ! response.item )
                return;

            // Change title
            //$('h4.header').html( 'Editar item' );

            // Populates the form
            $('input[name=id]').val( response.item.id );
            $('input[name=parent_id]').val( response.item.parent_id );
            $('#name').val( response.item.name );
            $('#target').val( response.item.target ).trigger('change');
            $('#page_id').val( response.item.page_id );
            $('#url').val( response.item.url );

            setFieldsDisplay( response.item.target );

            $('#fritem').attr('action', response.form_action);
            $('input[name=_method]').val( 'PUT' );
        }
    </script>
@endsection
