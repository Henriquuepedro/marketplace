@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="banner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <img src="{{ asset( $main_category->cover->url ) }}" title="{{ $main_category->name }}" alt="{{ $main_category->name }}">
            </div>
            <div class="col-lg-4 col-md-4">
                {!! build_subcategories( $main_category ) !!}
            </div>
        </div>
    </div>
</section>

<section class="product">
    <div class="container">
        <div class="row products">
            <x-products :products="$products" :uid="$user->id"></x-products>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        /**
         * Loads products of given category
         */
        function loadProducts( _category )
        {
            console.log( _category );

            var url = BASE_URL + '/products';
            var prs = $.param({ category: _category });

            Common.ajax( url, 'GET', prs, null, handleProducts );
        }

        /**
         * Shows loaded products
         */
        function handleProducts( json )
        {
            console.log( json );

            if( json.success === false )
                return;

            if( ! json.products )
                return;

            if( json.products.data.length === 0 )
                return;

            // Hide the message
            $('div.col.none').hide();

            // Alias to products data
            var $tgt  = $('div.row.products');
            var items = json.products.data;
            var html;

            for( var i = 0; i < items.length; i++ )
            {
                html = '<div class="col-lg-3 col-md-4 col-sm-6">'
                     +   '<div class="product__item">'
                     +     '<div class="product__item__pic set-bg" style="background-image: url('+ BASE_URL + items[i].url +');">';

                if( items[i].new === 'yes' )
                {
                    html += '<div class="label new">Novo</div>';
                }
                else if( items[i].eco_friendly === 'yes' )
                {
                    html += '<div class="label eco">Eco Friendly</div>';
                }

                html += '<ul class="product__hover">';

                if( items[i].url )
                {
                    html += '<li><a href="'+ BASE_URL + items[i].url +'" class="image-popup"><span class="fa fa-eye"></span></a></li>';
                }

                html +=         '<li><a href="#"><span class="fa fa-heart-o"></span></a></li>'
                      +         '<li><a href="#"><span class="fa fa-heart-o"></span></a></li>'
                      +       '</ul>'
                      +     '</div>'
                      +     '<div class="product__item__text">'
                      +       '<h6><a href="'+ BASE_URL + '/' + items[i].slug +'">'+ items[i].name +'</a></h6>'
                      +       '<p>'+ items[i].store_name +'</p>'
                      +       '<div class="product__price">R$ '+ items[i].price +'</div>'
                      +     '</div>'
                      +   '</div>'
                      + '</div>';

                // Append
                $tgt.append( $( html ) );
            }

            // Restart magnificPopup
            $('.image-popup').magnificPopup({
                type: 'image'
            });
        }

        $(document).ready(function(){
            //
            //loadProducts( {{-- $category->id --}} );
        });
    </script>
@endsection
