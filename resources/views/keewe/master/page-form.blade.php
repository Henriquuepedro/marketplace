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
                        <h5 class="title2">
                            @if( isset($page) )
                                Página: {{ $page->title }}
                            @else
                                Nova página
                            @endif
                        </h5>
                        <p>&nbsp;</p>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $page->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="title">Título da página</label>
                                    <input id="title" name="title" type="text" class="form-control" value="{{ $page->title ?? '' }}">
                                    <small class="form-text text-muted">Digite o título da página</small>
                                </div>
                                <div class="form-group col">
                                    <label for="slug">URL Amigável</label>
                                    <input id="slug" name="slug" type="text" class="form-control" value="{{ $page->slug ?? '' }}">
                                    <small class="form-text text-muted">Esse campo é preenchido automaticamente</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="meta_description">Descrição resumida para o Google</label>
                                    <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{ $page->meta_description ?? '' }}">
                                    <small class="form-text text-muted">Descreva, resumidamente, do se trata essa página</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="meta_keywords">Palavras-chave para o Google</label>
                                    <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{ $page->meta_keywords ?? '' }}">
                                    <small class="form-text text-muted">Separe as palavras-chave com vírgula</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="content">Conteúdo</label>
                                    <textarea id="content" name="content" class="form-control editor">{{ $page->content ?? '' }}</textarea>
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
                                        {{ ($action === 'update' ? 'atualizar' : 'cadastrar') . ' página' }}
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
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function(){
            //
            Common.slugify( '#title', '#slug', 'slugify' );
        });
    </script>
@endsection
