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
                        <h5 class="title2">Produtos esgotados</h5>

                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            @if( count($products) > 0 )
                                @foreach( $products as $product )
                                    @php
                                        $main_image = $product->mainImage()->url ?? 'media/general/product-placeholder.png';
                                    @endphp
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="product__item">
                                            <div class="product__item__pic set-bg" data-setbg="{{ asset( $main_image ) }}">

                                                @if( $product->isNew() )
                                                    <div class="label new">Novo</div>
                                                @endif

                                                @if( $product->isEcoFriendly() )
                                                    <div class="label eco">Eco Friendly</div>
                                                @endif

                                                <ul class="product__hover">
                                                    @if( $product->mainImage() )
                                                        <li><a href="{{ url('/produtos/'. $product->id .'/edit') }}"><span class="fa fa-edit"></span></a></li>
                                                    @endif
                                                    <!--
                                                    <li><a href="#"><span class="fa fa-heart-o"></span></a></li>
                                                    <li><a href="#"><span class="fa fa-shopping-bag"></span></a></li>
                                                    -->
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                <h6><a href="{{ url('/produtos/'. $product->id .'/edit') }}">{{ $product->name }}</a></h6>
                                                <p></p>
                                                <div class="product__price">R$ {{ $product->price }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col">
                                    <p class="lead">Nenhum produto aqui.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
