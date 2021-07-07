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
                        <h5 class="title2">Configurações da Loja</h5>
                        <p>
                            Aqui você pode personalizar a aparência da página da sua loja. Capriche!
                        </p>
                        <form method="POST" action="{{ url('/minha-loja/config') }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="">Fundo da página da Loja</label>
                                    &nbsp; <a href="#" class="" data-toggle="modal" data-target="#bg-modal">Escolher fundo</a>
                                    <br>
                                    @if( $store->background_id )
                                        <div class="bg-preview set-bg" data-setbg="{{ asset($store->background->url) }}"></div>
                                    @endif
                                </div>
                            </div>

                            <!-- Vertically centered scrollable modal -->
                            <div class="modal fade" id="bg-modal" tabindex="-1" aria-labelledby="bg-modal-title" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bg-modal-title">Escolha um fundo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <p>Selecione a imagem de fundo para a página da sua loja.</p>
                                                <div class="row">
                                                    @foreach( $backgrounds as $bg )
                                                        <div class="col-md-4 mb-4 text-center">
                                                            <div class="bg-item set-bg {{ ($bg->id === $store->background_id ? 'selected' : '') }}" data-refid="{{ $bg->id }}" data-setbg="{{ asset( $bg->url ) }}"></div>
                                                            <span>{{ $bg->name }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="">Imagem de Capa da Loja</label>
                                    <br>
                                    @php
                                        $source = $store->cover_id ? $store->cover : null;
                                    @endphp
                                    <x-dropzone :source="$source" name="cover_id" delete="true"></x-dropzone>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="">Logotipo da Loja</label>
                                    <br>
                                    @php
                                        $logo = $store->logo_id ? $store->logo : null;
                                    @endphp
                                    <x-dropzone :source="$logo" name="logo_id" delete="true"></x-dropzone>
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
            // Background
            $('.bg-item').off().on('click', function(){
                //
                var refid = $(this).attr('data-refid');

                //console.log( refid );
                setImage( 'background', refid );
            });

            // Cover
            $('input[name=cover_id]').off().on('change', function(){
                //
                var cover_id = $(this).val();

                //console.log( 'Cover changed to ' + cover_id );
                setImage( 'cover', cover_id );
            });

            // Logo
            $('input[name=logo_id]').off().on('change', function(){
                //
                var logo_id = $(this).val();

                //console.log( 'Logo changed to ' + logo_id );
                setImage( 'logo', logo_id );
            });
        });

        // BACKGROUND =========================================================
        /**
         * Sends image to backend
         */
        function setImage( _type, _id )
        {
            var url = BASE_URL + '/minha-loja/config';

            Common.ajax( url, 'POST', {type: _type, imgid: _id}, null, onImageSetted );
        }

        /**
         * Handler for Image setted
         */
        function onImageSetted( json )
        {
            if( json.success === false )
                return;

            //$('#bg-modal').find('button.close').trigger('click');

            document.location.reload();
        }
    </script>
@endsection
