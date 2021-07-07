@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">
                    <h5>Seja muito bem-vindo!</h5>
                    <p>
                        Seu cadastro foi validado e sua conta está ativa.
                        <br>
                        A partir de agora você pode comprar a valer!
                    </p>

                    <p class="mt-5">
                        <a class="btn btn-secondary" href="{{ url('/entrar') }}">Entre na sua conta</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
