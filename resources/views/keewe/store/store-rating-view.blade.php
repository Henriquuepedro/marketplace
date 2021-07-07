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

                        <div class="col-12">
                            <div class="products-wrapper">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Sua avaliação da loja</h4>
                                        <p>
                                            Veja abaixo sua avaliação da loja {{ $store->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><p>&nbsp;</p></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <label for="service">Atendimento</label>
                                        <br>
                                        <select id="service" name="service" class="ratings">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label for="products">Produtos</label>
                                        <br>
                                        <select id="products" name="products" class="ratings">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label for="shipping">Entregas</label>
                                        <br>
                                        <select id="shipping" name="shipping" class="ratings">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label for="after_sales">Pós-venda</label>
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
                                        <label for="review">Seus comentários</label>
                                        <textarea id="review" name="review" class="form-control" rows="4" readonly>{{ $rating->review }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            $('#service').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $rating->service }},
                readonly: true
            });

            $('#products').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $rating->products }},
                readonly: true
            });

            $('#shipping').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $rating->shipping }},
                readonly: true
            });

            $('#after_sales').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $rating->after_sales }},
                readonly: true
            });
        });
</script>
@endsection
