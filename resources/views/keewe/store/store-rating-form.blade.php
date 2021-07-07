@extends('keewe.layout.master')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-bar-rating/dist/themes/fontawesome-balls.css') }}" type="text/css">
@endsection

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container-fluid store-bricks" style="background-image: url({{ asset('media/backgrounds/fundo-keewe.png') }});">
        <div class="row">
            <div class="col-12">

                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="store-cover" style="background-image: url({{ asset('media/general/toldo-keewe.png') }});">
                                <h1>{{ $store->name }}</h1>
                            </div>
                        </div>

                        @if( $can_rating )
                            <div class="col-12">
                                <div class="products-wrapper">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>Avalie a loja</h4>
                                            <p>
                                                Para cada uma das áreas abaixo, atribua uma classificação para a loja,
                                                seguindo a legenda abaixo:
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row star-caption">
                                        <div class="col-md-2 col-xs-12">
                                            <i class="fa fa-star"></i> = Péssimo
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i> = Ruim
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> = Regular
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> = Bom
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> = Excelente
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><p>&nbsp;</p></div>
                                    </div>
                                    <form method="POST" action="{{ url('/store-ratings') }}" accept-charset="utf-8" onsubmit="return false;">
                                        @csrf
                                        <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? '' }}">
                                        <input type="hidden" name="referer" value="{{ request()->path() }}">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="service">1. Avalie o <b>Atendimento</b> da loja</label>
                                                <br>
                                                <select id="service" name="service" class="ratings">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="products">2. Avalie a qualidade dos <b>Produtos</b> da loja</label>
                                                <br>
                                                <select id="products" name="products" class="ratings">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="shipping">3. Avalie a pontualidade nas <b>Entregas</b> da loja</label>
                                                <br>
                                                <select id="shipping" name="shipping" class="ratings">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-xs-12">
                                                <label for="after_sales">4. Avalie o serviço de <b>Pós-venda</b> da loja</label>
                                                <br>
                                                <select id="after_sales" name="after_sales" class="ratings">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label for="review">Deixe seus comentários no campo abaixo</label>
                                                <textarea id="review" name="review" class="form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4 offset-md-4 col-xs-12">
                                                <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">enviar avaliação</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="products-wrapper">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>Avalie a loja</h4>
                                            <br><br>
                                            <p>
                                                Para fazer uma boa avaliação você precisa ter adquirido 
                                                ao menos 1 produto dessa loja. 
                                            </p>
                                            <p>
                                                Só assim você poderá ter uma 
                                                ideia melhor sobre a qualidade dos serviços prestados 
                                                pelo vendedor e conseguirá fazer uma avaliação correta.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
<script src="{{ asset('vendor/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
<script>
    $(document).ready(function(){
            //
            // Ratings
            $('.ratings').barrating({
                theme: 'fontawesome-stars',
                initialRating: 0
            });
        });
</script>
@endsection
