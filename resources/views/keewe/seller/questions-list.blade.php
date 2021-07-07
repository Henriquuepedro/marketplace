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
                        <h5 class="title2">Perguntas</h5>

                        <!--
                        <form method="GET" action="{{ url('/pedidos') }}" accept-charset="utf-8">
                            <input type="hidden" name="page" value="{{ request()->page ?? 1 }}">

                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="q">Procurar</label>
                                    <input id="q" name="q" type="text" class="form-control" value="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="category">Categoria</label>
                                    <select id="category" name="category" palceholder="Categoria" class="form-control select2">
                                        <option value="">Todas</option>
                                        {!! cbo_categories( $categories ) !!}
                                    </select>
                                </div>
                                <div class="form-group col-md-3 text-right">
                                    <a href="{{ url('/produtos/create') }}" class="btn btn-primary">adicionar produto</a>
                                </div>
                            </div>
                        </form>
                        -->

                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        @if( count($questions) )
                            @foreach( $questions as $question )
                                <div class="row">
                                    <div class="col-5">
                                        <dl class="row">
                                            <dt class="col-4">Produto</dt>
                                            <dd class="col-8">
                                                <a href="{{ url('/produtos/' . $question->product->id . '/edit') }}"> {{ $question->product->name }} </a>
                                            </dd>

                                            <dt class="col-4">Usuário</dt>
                                            <dd class="col-8">{{ $question->user->fullname }}</dd>

                                            <dt class="col-4">Data</dt>
                                            <dd class="col-8">{{ dtf($question->created_at, 'd/m/Y') }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-7">
                                        <dl>
                                            <dt>Pergunta</dt>
                                            <dd>{{ $question->question }}</dd>

                                            @if( $question->answer )
                                                <dt>Sua resposta</dt>
                                                <dd>{{ $question->answer }}</dd>
                                            @else
                                                <dt></dt>
                                                <dd>
                                                    <form method="POST" action="{{ url('/perguntas/'. $question->id) }}" accept-charset="utf-8" onsubmit="return false;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $question->id }}">
                                                        <textarea name="answer" class="form-control" maxlength="255" placeholder="Digite a resposta aqui"></textarea>
                                                        <button type="button" class="btn btn-secondary mt-2" onclick="Common.postForm(this, Common.saveHandler);">responder</button>
                                                    </form>
                                                </dd>
                                            @endif
                                        </dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><hr></div>
                                </div>

                            @endforeach
                        @else
                            <div class="row">
                                <div class="col">
                                    Por enquanto você não tem perguntas pendentes.
                                </div>
                            </div>
                        @endif

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
        function delItem( item_id )
            {
                var url = BASE_URL + '/pedidos/' + item_id;

                Common.delete( url, {} );
            }
    </script>
@endsection
