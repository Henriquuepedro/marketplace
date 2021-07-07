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
                            @if( isset($menu) )
                                Menu: {{ $menu->name }}
                            @else
                                Novo Menu
                            @endif
                        </h5>
                        <p>&nbsp;</p>

                        <form method="POST" action="{{ $form_action }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            @if( $action === 'update' )
                                @method('PUT')
                            @endif

                            <input type="hidden" name="id" value="{{ $menu->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="name">Nome do Menu</label>
                                    <input id="name" name="name" type="text" class="form-control" value="{{ $menu->name ?? '' }}">
                                    <small class="form-text text-muted">O nome do Menu será exibido como título da coluna</small>
                                </div>
                                <div class="form-group col-6">
                                    <label for="position">Posição</label>
                                    <select id="position" name="position" palceholder="Posição" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options_data( $position_options, ($menu->position ?? null) ) !!}
                                    </select>
                                    <small class="form-text text-muted">Apenas o Rodapé é suportado</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="type">Tipo</label>
                                    <select id="type" name="type" palceholder="Tipo" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options_data( $type_options, ($menu->type ?? null) ) !!}
                                    </select>
                                    <small class="form-text text-muted">O tipo Categorias é construído automaticamente</small>
                                </div>
                                <div class="form-group col-6">
                                    <label for="order">Ordem</label>
                                    <input id="order" name="order" type="text" class="form-control msk-int" value="{{ $menu->order ?? '' }}">
                                    <small class="form-text text-muted">Ordem do menu</small>
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
                                        {{ ($action === 'update' ? 'atualizar' : 'cadastrar') . ' menu' }}
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
