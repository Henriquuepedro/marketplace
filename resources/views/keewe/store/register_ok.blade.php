@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">
                    <p>
                        Em breve você receberá um email com instruções para ativar sua conta.
                        Basta clicar no link enviado para validar seu cadastro.
                        <br>
                        Caso não encontre a mensagem na caixa de entrada, verifique sua caixa de spam.
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
