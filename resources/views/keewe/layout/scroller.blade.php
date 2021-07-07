<!-- Categories Section -->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Categorias</span></h4>
                </div>
            </div>
        </div>
    </div>
    <div id="frame">
        <div id="slideshow">
            @foreach( $categories as $categ )

                @if( ! is_null( $categ->parent_id ) )
                    @continue
                @endif

                <img src="{{ asset( $categ->cover->url ) }}" width="300" onclick="Common.toUrl('{{ url('/categoria/' . $categ->slug) }}');">

            @endforeach

            <!-- repeat for infinite scroll -->
            @foreach( $categories as $categ )

                @if( ! is_null( $categ->parent_id ) )
                    @continue
                @endif

                <img src="{{ asset( $categ->cover->url ) }}" width="300" onclick="Common.toUrl('{{ url('/categoria/' . $categ->slug) }}');">

            @endforeach
        </div>
    </div>
</section>
<!--
<div class="instagram">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h4 class="lined"><span>Categorias</span></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div id="scroller" class="slider">
            <ul class="slider-list list-unstyled">
                @foreach( $categories as $categ )
                    @if( ! is_null( $categ->parent_id ) )
                        @continue
                    @endif
                    <li class="slider-item">
                        <img src="{{ asset( $categ->cover->url ) }}" width="300">
                        <div class="slider-hover">
                            <a href="{{ url('/categoria/' . $categ->slug) }}">{{ $categ->name }}</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
-->
<!-- Categories Section End -->