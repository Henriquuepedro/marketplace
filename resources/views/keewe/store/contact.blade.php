@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-7">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">
                    <form method="POST" action="{{ url('/fale-conosco') }}" accept-charset="utf-8" onsubmit="return false;">
                        @csrf
                        @if( $user )
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <input type="text" name="name" placeholder="Seu Nome" class="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="email" name="email" placeholder="Seu E-mail">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="city" placeholder="Sua Cidade">
                            </div>
                            <div class="col-4">
                                <select name="state" palceholder="Estado" class="form-control select2">
                                    <option>Selecione</option>
                                    {!! options( 'App\Models\Location\State', 'name', 'code', 'name' ) !!}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <textarea name="message" placeholder="Digite sua mensagem aqui" class=""></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-between">
                            <div class="col-5">
                                <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">enviar mensagem</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-lg-5 col-md-5">
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
