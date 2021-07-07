@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">

                    {!! $page->content !!}

                    <p>
                        Última atualização deste documento: {{ dtf( $page->updated_at, 'd.m.Y' ) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
