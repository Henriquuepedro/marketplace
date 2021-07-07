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
                    <div class="col-md-5">
                        <h5 class="title2">Reputação</h5>
                        <br>
                        @for( $i = 1; $i <= 5; $i++ )
                            <i class="fa fa-2x fa-star {{ ($ratings->average >= $i) ? 'star-orange' : 'star-gray' }}"></i>
                        @endfor
                        <br><br>
                        <p>
                            <!--
                            Parabéns.
                            <br>
                            -->
                            Sua reputação está no <big><b>Nível {{ $ratings->average }}</b></big>
                        </p>
                        <!--
                        <p>
                            Isso mostra que seu bom trabalho está sendo reconhecido pelos clientes,
                            através das avaliações dos produtos, do atendimento, entrega e pós-venda.
                        </p>
                        -->
                        <br>
                        <h5 class="title3">Conheça os Níveis</h5>
                        <p>
                            Conheça abaixo os níveis que você pode alcançar.
                        </p>
                        <br>
                        <p class="rating-levels align-middle">
                            @for( $x = 1; $x <= 5; $x++ )
                                @for( $y = 1; $y <= $x; $y++ )
                                    <i class="fa fa-fw fa-star"></i>
                                @endfor
                                &nbsp; Nível {{ $x }}
                                <br>
                            @endfor
                        </p>
                        <br>
                        <h5 class="title3">Porque eu devo me preocupar com isso?</h5>
                        <p>
                            Você terá maior probabilidade de converter vendas se tiver boas avaliações.
                            Preste atenção nos 4 itens de avaliação e trabalhe para melhorá-los sempre.
                            Ter produtos de boa qualidade e cumprir os prazos de entrega contribuem
                            bastante para uma boa avaliação. Atender bem os clientes e ter um bom
                            pós-venda faz toda a diferença.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="title3">Detalhe das avaliações</h5>
                        <br>
                        <div class="row ratings-header">
                            <div class="col-4">Itens avaliados</div>
                            <div class="col-2">Ruim</div>
                            <div class="col-2">Regular</div>
                            <div class="col-2">Bom</div>
                            <div class="col-2">Ótimo</div>
                        </div>
                        <div class="row ratings-area">
                            <div class="col-4">Atendimento</div>
                            <div class="col-8">
                                <div class="progress-bar"><span style="width: {{ $ratings->service_pct }}%"></span></div>
                            </div>
                        </div>
                        <div class="row ratings-area">
                            <div class="col-4">Produtos</div>
                            <div class="col-8">
                                <div class="progress-bar"><span style="width: {{ $ratings->products_pct }}%"></span></div>
                            </div>
                        </div>
                        <div class="row ratings-area">
                            <div class="col-4">Entrega</div>
                            <div class="col-8">
                                <div class="progress-bar"><span style="width: {{ $ratings->shipping_pct }}%"></span></div>
                            </div>
                        </div>
                        <div class="row ratings-area">
                            <div class="col-4">Pós-venda</div>
                            <div class="col-8">
                                <div class="progress-bar"><span style="width: {{ $ratings->after_sales_pct }}%"></span></div>
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
