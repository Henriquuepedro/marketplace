@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4>{{ $page_title }}</h4>
                <div class="contact__form">

                    <div class="row store-index">
                        @foreach( $alphabet as $letter )
                            <div class="col-lg-3 col-md-3">
                                <h5>{{ $letter }}</h5>

                                @foreach( $stores as $store )

                                    @php
                                        $first = strtoupper( substr( $store->name, 0, 1 ) );
                                    @endphp

                                    @if( $first > $letter )
                                        @break
                                    @elseif( $first === $letter )
                                        <a href="{{ url('/lojas/' . $store->slug) }}" class="">{{ $store->name }}</a> <br>
                                    @endif

                                @endforeach
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
