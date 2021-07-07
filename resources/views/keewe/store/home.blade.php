@extends('keewe.layout.master')

@section('content')
<!-- Banner Section Begin -->
<section class="banner set-bg" data-setbg="{{ asset('media/banners/banner-home.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto banner-content text-center">
                <p class="align-middle text-white">
                Ser cool é respeitar sua <br>
                autenticidade e o mundo.
                </p>
                <a href="{{ url('/novidades') }}" class="btn btn-secondary">novidades do site</a>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->

<!-- Newer products -->
<section class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Novidades</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <x-products :products="$new_products" :uid="$user->id"></x-products>
        </div>
    </div>
</section>
<!-- Newer products End -->

<!-- More products -->
<section class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Gifts para Ela</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <x-products :products="$gifts_for_her" :uid="$user->id"></x-products>
        </div>
    </div>
</section>
<!-- More products End -->

<!-- Second Banner Section Begin -->
<section class="banner set-bg" data-setbg="{{ asset('media/banners/home-joia.png') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 banner-content dyn-y">
                <p class="text-left">
                Criações únicas de artistas <br>
                & designers independentes.
                </p>
                <p class="text-left phrase">
                    Disponível para a Grande São Paulo <br>
                    e em breve para todo o Brasil!
                </p>
                <a href="{{ url('/novidades') }}" class="btn btn-secondary">novidades do site</a>
            </div>
        </div>
    </div>
</section>
<!-- Second Banner Section End -->

<!-- More products -->
<section class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Gifts para Kids</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <x-products :products="$gifts_for_kids" :uid="$user->id"></x-products>
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
                    <h6>Itens únicos e cheios de personalidade</h6>
                    <p>
                        Feitos em baixa escala pelas mãos <br>
                        de pequenos empreendedores <br>
                        nacionais.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Vendedores independentes</h6>
                    <p>
                        Diz a lenda que a cada venda que <br>
                        nossos artistas concluem, eles <br>
                        fazem uma dancinha feliz.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Compra segura</h6>
                    <p>
                        A mais alta tecnologia e <br>
                        segurança para suas compras <br>
                        não virarem perrengues.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

@include('keewe.layout.scroller')

@endsection