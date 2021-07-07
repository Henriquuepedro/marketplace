<section class="">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item static">Você está em:</li>
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Keewe</a></li>
                @foreach( $breadcrumb as $item )

                    @if( $loop->last )

                        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>

                    @else

                        <li class="breadcrumb-item"><a href="{{ $item->url }}">{{ $item->name }}</a></li>

                    @endif

                @endforeach


            </ol>
        </nav>
    </div>
</section>