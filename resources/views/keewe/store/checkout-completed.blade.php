@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row mb-4">
            <div class="col-8 offset-2 text-center">
                <h4>{{ $page_title }}</h4>
                <p>&nbsp;</p>
                <p class="lead">
                    O número do seu pedido é: <big><strong>{{ $order->tid }}</strong></big>
                </p>
                @if( $order->payment_method === 'boleto' )
                    <p>
                        Código de barras do boleto:
                    </p>
                    <p class="lead">
                        <strong>{{ $order->boleto_barcode }}</strong>
                        <br>
                        <a href="{{ $order->boleto_url }}">Clique aqui para abrir o boleto</a>
                    </p>
                @endif
                <p>
                    Lembrando que o prazo de entrega só será considerado à partir
                    da data de aprovação do pagamento na sua operadora de cartão de crédito.
                    <!--
                    Lembrando que o prazo de entrega só será considerado à partir da data de confirmação
                    do pagamento do boleto ou aprovação do pagamento na sua operadora de cartão de crédito.
                    -->
                </p>
                <p>
                    Obrigado por comprar conosco!
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    @parent
@endsection
