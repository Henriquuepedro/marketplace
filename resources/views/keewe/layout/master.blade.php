<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="keywords" content="{{ $meta_keywords }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <title>{{ $site_title }}</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;700&display=swap" rel="stylesheet">
    <!--
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    -->

    <!-- Css Styles -->
    @section('styles')
        <!-- START: vendors -->
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{-- asset('vendor/jquery-ui/jquery-ui.min.css') --}}" type="text/css">
        <link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}" type="text/css">
        <link rel="stylesheet" href="{{-- asset('vendor/owl-carousel/assets/owl.carousel.min.css') --}}" type="text/css">
        <link rel="stylesheet" href="{{ asset('vendor/slick-nav/slicknav.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" type="text/css">
        <!-- Editor & File Input -->
        <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('vendor/dropzone/dist/min/dropzone.min.css') }}" type="text/css">
        <!-- END: vendors -->

        <link rel="stylesheet" href="{{ asset('keewe/css/style.css?v=' . md5(microtime())) }}" type="text/css">
        @if( is_home() )
            <link rel="stylesheet" href="{{ asset('keewe/css/style_home.css?v='. md5(microtime())) }}" type="text/css">
            <link rel="stylesheet" href="{{ asset('keewe/css/carousel.css') }}" type="text/css">
        @else
            <link rel="stylesheet" href="{{ asset('keewe/css/style_store.css?v=' . md5(microtime())) }}" type="text/css">
        @endif
    @show
</head>
<body class="{{ (is_home() ? 'store-home' : 'store') }}">
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <div class="cover-wrapper">

        <!-- Header Section -->
        @include('keewe.layout.header')

        <!-- Categories & Search Section -->
        @php
            $path = request()->path();
        @endphp
        @if( ($path != 'entrar') && ($path != 'carrinho') && ($path != 'checkout') )
            @include('keewe.layout.categories')
        @endif'

        <!-- Carousel Section -->
        @if( is_home() )
            @include('keewe.layout.carousel')
        @endif

        <!-- Breadcrumb Section -->
        @if( ! is_home() )
            @include('keewe.layout.breadcrumb')
        @endif

    </div>

    @yield('content')

    @include('keewe.layout.footer')

    <!-- Js Plugins -->
    @section('scripts')
        <!-- START: vendors -->
        <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{-- asset('vendor/jquery-ui/jquery-ui.min.js') --}}"></script>
        <script src="{{-- asset('vendor/mixitup/mixitup.min.js') --}}"></script>
        <script src="{{-- asset('vendor/jquery-countdown/jquery.countdown.min.js') --}}"></script>
        <script src="{{ asset('vendor/slick-nav/jquery.slicknav.js') }}"></script>
        <script src="{{-- asset('vendor/owl-carousel/owl.carousel.min.js') --}}"></script>
        <script src="{{-- asset('vendor/jquery-nicescroll/jquery.nicescroll.min.js') --}}"></script>

        <!-- <script src="{{-- asset('vendor/image-slider/jquery.imageslider.js') --}}"></script> -->

        <!-- Editor & File Input -->
        <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('vendor/summernote/lang/summernote-pt-BR.min.js') }}"></script>
        <script src="{{ asset('vendor/dropzone/dist/min/dropzone.min.js') }}"></script>

        <!-- Masks -->
        <script src="{{ asset('vendor/jquery-mask/dist/jquery.mask.min.js') }}"></script>

        <!-- Select2 -->
        <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('vendor/select2/dist/js/i18n/pt-BR.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.all.min.js"></script>
        <!-- END: vendors -->

        <script>
            var BASE_URL = '{{ $base_url }}';
        </script>
        <script src="{{ asset('vendor/utils/common.js') }}"></script>
        <script src="{{ asset('vendor/utils/uploader.js') }}"></script>
        <script src="{{ asset('keewe/js/app.js?v=' . md5(microtime())) }}"></script>
    @show

</body>
</html>