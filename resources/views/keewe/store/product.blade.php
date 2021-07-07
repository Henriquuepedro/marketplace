@extends('keewe.layout.master')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-bar-rating/dist/themes/fontawesome-balls.css') }}" type="text/css">
@endsection

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container product">
        <div class="row">
            <div class="col-md-7 col-xs-12">
                <div id="main-image">
                    @if( $product->mainImage() )
                        <img src="{{ asset($product->mainImage()->url) }}" alt="">
                    @else
                        <img src="{{ asset('media/general/product-placeholder.png?t=') . md5(microtime()) }}" alt="">
                    @endif
                </div>
            </div>
            <div class="col-md-1 col-xs-12 col-thumbs">
                <ul class="list-unstyled product-thumbs">
                    @foreach( $product->images as $thumb )
                        <li>
                            <a href="#" class="product-thumb"><img src="{{ asset($thumb->url) }}" alt=""></a>
                        </li>
                    @endforeach
                </ul>
                <ul class="list-unstyled product-stamps">
                    @if( $product->isCrueltyFree() )
                        <li>
                            <img src="{{ asset('media/selos/cruelty-free-laranja.png') }}" alt="" title="Cruelty Free">
                        </li>
                    @endif
                    @if( $product->isEcoFriendly() )
                        <li>
                            <img src="{{ asset('media/selos/eco-friendly-laranja.png') }}" alt="" title="Eco Friendly">
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4 col-xs-12">
                <h1 class="product-name">{{ $product->name }}</h1>
                <p class="store-name">{{ $product->shop->name }}</p>
                @if( ($product->quantity <= 0) || ($product->shop->status != 'active') )
                    <p class="product-price">
                        Indisponível
                    </p>
                @else
                    <p class="product-price">
                        {{ fmoney( $product->price() ) }}
                    </p>

                    <form method="POST" action="{{ url('/carts') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <!-- Product variations -->
                        @if( $product->variations )

                            @foreach( $product->variations as $variation )
								@if( $variation->status != 'active' )
									@continue
								@endif

                                <div class="form-group">
                                    <label for="var-{{ $variation->id }}">{{ $variation->name }}</label>
                                    <select id="var-{{ $variation->id }}" name="variation[{{ $variation->id }}]" class="form-control select2">
                                        @foreach( $variation->options as $option )

                                            @if( $option->quantity <= 0 )
                                                <option value="{{ $option->id }}" disabled>{{ $option->name }} (indisponível)</option>
                                                @continue
                                            @endif

                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            @endforeach

                        @endif
                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity">Quantidade</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary btn-block" onclick="Common.postForm(this, countCartItems);">colocar no carrinho</button>
                        </div>
                    </form>
                @endif


            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-xs-12">
                <ul class="nav nav-tabs" id="product-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-1" data-toggle="tab" href="#pane-1" role="tab" aria-controls="home" aria-selected="true">Detalhes do Produto</a>
                    </li>
                    <!--
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-2" data-toggle="tab" href="#pane-2" role="tab" aria-controls="profile" aria-selected="false">Entrega e Devoluções</a>
                    </li>
                    -->'
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-3" data-toggle="tab" href="#pane-3" role="tab" aria-controls="contact" aria-selected="false">Perguntas e Respostas</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pane-1" role="tabpanel" aria-labelledby="tab-1">
                        {!! $product->description !!}
                    </div>
                    <!--
                    <div class="tab-pane fade" id="pane-2" role="tabpanel" aria-labelledby="tab-2">...</div>
                    -->
                    <div class="tab-pane fade" id="pane-3" role="tabpanel" aria-labelledby="tab-3">
                        <p>
                            Antes de fazer uma pergunta, verifique se outra pessoa já teve a mesma dúvida e se ela
                            foi respondida abaixo.
                        </p>
                        @if( auth()->guest() )
                            <a href="{{ url('/entrar?t=' . $product->slug) }}"><big><b>Entre na sua conta</b></big></a> para fazer perguntas.
                        @else
                            <form method="POST" action="{{ url('/questions') }}" accept-charset="utf-8" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="store_id" value="{{ $product->store_id }}">
                                <div class="form-row">
                                    <div class="form-group col-9">
                                        <input type="text" class="form-control" id="question" name="question" placeholder="Digite sua pergunta ou dúvida">
                                    </div>
                                    <div class="form-group col-2">
                                        <button type="submit" class="btn btn-secondary" onclick="Common.postForm(this, onQuestion);">perguntar</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                        <div id="questions-loader">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        </div>
                        <div id="question-scroller"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <p class="area-title">Sobre o Vendedor</p>
                <p class="store-name"><a href="{{ url('/lojas/' . $product->shop->slug) }}">{{ $product->shop->name }}</a></p>
                <address>
                    <i class="fa fa-map-marker"></i>
                    {{ $product->shop->address->city }} - {{ $product->shop->address->state->code }}
                </address>
                <p>{{ $product->shop->slogan }}</p>

                <div class="shadow-line"></div>

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
                            <a href="{{ url('/avalie?store=' . $product->shop->id) }}">Quero avaliar a loja</a>
                        </div>
                    </div>
                    <!--
                    <div class="row">
                        <div class="col-6">
                            <h6>Produtos à venda</h6>
                        </div>
                        <div class="col-6">
                            <h6>Produtos vendidos</h6>
                        </div>
                    </div>
                    -->
                    <div class="row">
                        <div class="col">
                            <h6>Reviews</h6>
                            <div class="reviews-wrapper">
                                @foreach( $product->shop->reviews() as $review )
                                <div class="row">
                                    <div class="col">
                                        <span class="review-user">{{ $review->user->fullname }}</span> -
                                        <span class="review-date">{{ dtf($review->created_at, 'd.m.Y') }}</span>
                                        <br>
                                        @for( $i = 1; $i <= 5; $i++ )
                                            <i class="fa fa-star {{ ($review->average >= $i) ? 'star-orange' : 'star-gray' }}"></i>
                                        @endfor
                                        <p class="review-text">{{ $review->review }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->

<!-- More products -->
<section class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Mais produtos da Loja</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <x-products :products="$shop_products" :uid="$user->id"></x-products>
        </div>
    </div>
</section>
<!-- More products End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Porque comprar na Keewe?</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Itens exclusivos</h6>
                    <p>
                        Milhares de produtos pra <br>
                        você buscar e encontrar
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Vendedores independentes</h6>
                    <p>
                        Compre produtos de vendedores <br>
                        que colocam muito carinho nos produtos
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Compra segura</h6>
                    <p>
                        Tecnologia e segurança <br>
                        para suas compras
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Categories Section -->
@include('keewe.layout.scroller')
<!-- Categories Section End -->

@endsection

@section('scripts')
    @parent
    <script src="{{ asset('vendor/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            //
            $('.product-thumb').off().on('click', function(){
                // Get image source
                var source = $(this).find('img').attr('src');
                // Show big
                $('#main-image').find('img').attr('src', source);
            });

            // Questions tab
            $('#tab-3').on('shown.bs.tab', function(event){
                //
                loadQuestions();
            });

            // Average Ratings
            $('#average-rating').barrating({
                theme: 'fontawesome-stars',
                initialRating: {{ $product->shop->averageRatings() ?? 0 }},
                readonly: true
            });

            // Shipping Ratings
            $('#shipping-rating').barrating({
                theme: 'fontawesome-balls',
                initialRating: {{ $product->shop->averageShippingRatings() ?? 0 }},
                readonly: true
            });
        });

        /**
         * Handler for Question post.
         */
        function onQuestion( json )
        {
            $('#question').val('');

            Common.responseHandler( json );
        }

        /**
         * Loads the questions
         */
        function loadQuestions()
        {
            // Removes previous content, if any & Show loader
            $('#question-scroller').empty();
            $('#questions-loader').show();

            var url  = 'questions';
            var args = { product_id: $('input[name=product_id]').val() };

            Common.get( url, args, '#question-scroller', onQuestionsLoaded );
        }

        /**
         * Handler for Questions loaded
         */
        function onQuestionsLoaded( json )
        {
            //console.log( json );
            if( json.success == false )
                return;

            // Hides loader
            $('#questions-loader').hide();

            // Alias to Question list
            var questions = json.questions.data;
            var html;

            if( questions.length === 0 )
            {
                $('#question-scroller').append( $('<h5>Nenhuma pergunta sobre esse produto.</h5>') );
                return;
            }
            else
            {
                $('#question-scroller').append( $('<h5 class="mb-3">Últimas perguntas</h5>') );
            }

            for( var i = 0; i < questions.length; i++ )
            {
                html = '<blockquote class="blockquote">'
                     +   '<p class="mb-0">'+ questions[i].question +'</p>'
                     +   '<footer class="blockquote-footer">'+ questions[i].answer +'</footer>'
                     + '</blockquote>';

                $('#question-scroller').append( $(html) );
            }
        }
    </script>
@endsection
