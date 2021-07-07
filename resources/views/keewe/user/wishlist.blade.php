@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h4>{{ $page_title }}</h4>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">

                        <!-- User menu -->
                        @include('keewe.user._nav')

                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <h5 class="title2">{{ $page_title }}</h5>
                        <br>

                        <div class="row cart">

                            @if( count($wishlist) > 0 )
                                <div class="col-9 cart-items">

                                    @foreach( $wishlist as $item )
                                        <div class="card mb-4">
                                            <div class="row no-gutters">
                                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 product-thumb">
                                                    <img src="{{ asset( $item->mainImageUrl() ) }}" class="card-img" alt="...">
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <a href="{{ url($item->slug) }}">{{ $item->name }}</a>
                                                        </h5>
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td>{{ fmoney( $item->price ) }}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-outline-warning" onclick="removeItem({{ $item->id }});" data-toggle="tooltip" data-placement="bottom" title="Remover item">
                                                                            <i class="fa fa-minus"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-12 mt-3">
                                    <p><big>Sua wishlist está vazia.</big></p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        // Global var
        var Fields = {
            _token: null,
            _method: 'DELETE'
        };

        $(document).ready(function(){
            //
            Fields._token = $('input[name=_token]').val();
        });

        /**
         * Removes item from Wishlist
         */
        function removeItem( item_id )
        {
            Fields.id = item_id;
            var url   = BASE_URL + '/wishlist/' + item_id;

            Common.ajax( url, 'POST', Fields, null, onItemRemoved );
        }

        /**
         * Handler for item removed
         */
        function onItemRemoved( json )
        {
            document.location.reload();
        }
    </script>
@endsection
