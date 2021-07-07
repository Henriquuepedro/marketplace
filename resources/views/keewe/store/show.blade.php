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
                                    <x-products :products="$products" :uid="$user->id"></x-products>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<section class="content-adm">
    <div class="container">
        <div class="row">
            <div class="col">
                <p class="area-title">Sobre o Vendedor</p>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3 col-xs-12">
                <p class="store-name"> {{ $store->name }} </p>
                <address>
                    <i class="fa fa-map-marker"></i>
                    {{ $store->address->city }} - {{ $store->address->state->code }}
                </address>
                <p>{{ $store->slogan }}</p>
            </div>

            <div class="col-md-4 col-xs-12">
                <!-- RATINGS -->
                <div class="store-ratings">
                    <div class="row">
                        <div class="col-6">
                            <h6>Avaliação</h6>
                            <select id="average-rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <h6>Tempo de entrega</h6>
                            <select id="shipping-rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center py-5">
                            <a href="{{ url('/avalie?store=' . $store->id) }}">Quero avaliar a loja</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-xs-12">
                <h6><b>Reviews</b></h6>
                <div class="reviews-wrapper">
                    @foreach( $store->reviews() as $review )
                    <div class="row">
                        <div class="col">
                            <span class="review-user">{{ $review->user->fullname }}</span> -
                            <span class="review-date">{{ dtf($review->created_at, 'd.m.Y') }}</span>
                            <br>
                            @for( $i = 1; $i <= 5; $i++ ) <i class="fa fa-star {{ ($review->average >= $i) ? 'star-orange' : 'star-gray' }}"></i>
                                @endfor
                                <p class="review-text">{{ $review->review }}</p>
                        </div>
                    </div>
                    @endforeach
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
            // Average Ratings
            $('#average-rating').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $store->averageRatings() ?? 0 }},
                readonly: true
            });

            // Shipping Ratings
            $('#shipping-rating').barrating({
                theme: 'fontawesome-balls',
                initialRating: {{ $store->averageShippingRatings() ?? 0 }},
                readonly: true
            });
        });
    </script>
@endsection
