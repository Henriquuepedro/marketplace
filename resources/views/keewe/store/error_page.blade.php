@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">

                    @if( $error )

                        <h5>Atenção</h5>
                        <p>{{ $error }}</p>

                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
