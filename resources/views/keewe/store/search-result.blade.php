@extends('keewe.layout.master')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/css/bootstrap-slider.min.css') }}" type="text/css">
@endsection

@section('content')
<!-- Content Section Begin -->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="subcategory-sidebar">
                    <x-categories-found :tree="$categories_found"></x-categories-found>

                    <h2>Faixa de preço</h2>
                    @if( $price_range->min_price == $price_range->max_price )
                        <input id="price-slider"
                               type="text"
                               data-slider-min="0"
                               data-slider-max="{{ $price_range->max_price }}"
                               data-slider-step="1"
                               data-slider-precision="2"
                               data-slider-value="{{ $price_range->max_price }}"
                               data-slider-enabled="false">
                    @else
                        <input id="price-slider"
                               type="text"
                               data-slider-min="{{ $price_range->min_price }}"
                               data-slider-max="{{ $price_range->max_price }}"
                               data-slider-step="1"
                               data-slider-precision="2"
                               data-slider-value="[{{ $price_range->min_price }},{{ $price_range->max_price }}]">
                    @endif

                    <h2>Selos</h2>
                    <div class="form-check">
                        <input class="form-check-input filters" type="checkbox" value="stamp-new" id="stamp-new" {{ ($filters && $filters->stp_n) ? 'checked' : '' }}>
                        <label class="form-check-label" for="stamp-new">Novo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input filters" type="checkbox" value="stamp-eco" id="stamp-eco" {{ ($filters && $filters->stp_e) ? 'checked' : '' }}>
                        <label class="form-check-label" for="stamp-eco">Eco-Friendly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input filters" type="checkbox" value="stamp-cruel" id="stamp-cruel" {{ ($filters && $filters->stp_c) ? 'checked' : '' }}>
                        <label class="form-check-label" for="stamp-cruel">Cruelty Free</label>
                    </div>

                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="container">
                    <div class="row products">
                        <x-products :products="$products" :uid="$user->id"></x-products>
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
    <script src="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
    <script>
        var priceSlider;

        $(document).ready(function(){
            // Price Slider
            priceSlider = $('#price-slider').bootstrapSlider({
                formatter: function(value)
                {
                    return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            });

            // Filters
            $('#price-slider').on('slideStop', function(){
                //
                getFilters();
            });

            $('.filters').on('change', function(){
                //
                getFilters();
            });
        });

        function getFilters()
        {
            // Get all filters
            var params = {
                prc_r: priceSlider.bootstrapSlider('getValue'),
                stp_n: $('#stamp-new').prop('checked'),
                stp_e: $('#stamp-eco').prop('checked'),
                stp_c: $('#stamp-cruel').prop('checked')
            };

            //console.log( params );

            var url = window.location.protocol +'//'+ window.location.host + window.location.pathname;
            var qst = JSON.stringify( params );
            var qry = $('input[name=q]').val();

            setTimeout( function(){
                //
                Common.toUrl( url + '?q='+ qry +'&f=' + qst );
            }, 1000 );
        }

    </script>
@endsection
