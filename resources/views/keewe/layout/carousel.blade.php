<section class="carousel">
    <div id="home-banners" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach( $carousel as $item )
                <li data-target="#home-banners" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach( $carousel as $item )
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}" style="background-image: url({{ asset($item->image) }});">
                    <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" onclick="Common.toUrl('{{ $item->url }}');">
                        <rect width="100%" height="100%" fill="none"></rect>
                    </svg>
                    <div class="container">
                        <div class="carousel-caption text-left">
                            <!--
                            <h1>Example headline {{ $loop->index }}.</h1>
                            <p>
                                Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.
                                Nullam id dolor id nibh ultricies vehicula ut id elit.
                            </p>
                            -->
                        </div>
                    </div>
                </div>
            @endforeach
            <!--
            <div class="carousel-item active">
                <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                    <rect width="100%" height="100%" fill="none"></rect>
                </svg>
                <div class="container">
                    <div class="carousel-caption">
                        <h1>Another example headline.</h1>
                        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        <p><a class="btn btn-lg btn-secondary" href="#" role="button">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                    <rect width="100%" height="100%" fill="none"></rect>
                </svg>
                <div class="container">
                    <div class="carousel-caption text-right">
                        <h1>One more for good measure.</h1>
                        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                        <p><a class="btn btn-lg btn-secondary" href="#" role="button">Browse gallery</a></p>
                    </div>
                </div>
            </div>
            -->
        </div>
        <a class="carousel-control-prev" href="#home-banners" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#home-banners" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Próximo</span>
        </a>
    </div>

</section>