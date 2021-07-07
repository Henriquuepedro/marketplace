<section class="categories">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-block text-left" onclick="toogleCategories();">
                    CATEGORIAS
                    <i class="fa fa-bars float-right pt-1"></i>
                </button>
            </div>
            <div class="col-md-9">
                <form method="GET" action="{{ url('/busca') }}" accept-charset="utf-8" class="search-form">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar..." name="q" aria-label="Buscar" value="{{ $query ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-gray" type="submit" id="search-button"><span class="fa fa-search"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row megamenu" id="mega">
            <div class="col">
                <div id="categories">
                    <div class="row">
                        <div class="col">
                            <span class="pull-left">CATEGORIAS</span>
                            <a id="close-mega" class="pull-right" href="javascript:toogleCategories();"><i class="fa fa-close"></i></a>
                            <br><br>
                        </div>
                    </div>

                    <div class="row">
                        {!! build_categories( $categories ) !!}
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div id="line-two" class="row">
            <div class="col-12">
                <div id="categories">
                    <div class="row">
                        <div class="col">
                            <span class="pull-left">CATEGORIAS</span>
                            <br><br>
                        </div>
                    </div>

                    <div class="row">
                        {!! build_categories( $categories ) !!}
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</section>