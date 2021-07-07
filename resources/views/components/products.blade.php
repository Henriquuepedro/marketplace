@if( count($products) > 0 )
    @foreach( $products as $product )
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
            <div class="product__item">
                <div class="product__item__pic set-bg" data-setbg="{{ asset( $product->mainImageUrl() ) }}">

                    @if( $product->isNew() )
                        <div class="label new">Novo</div>
                    @endif

                    @if( $product->isEcoFriendly() )
                        <div class="label eco">Eco Friendly</div>
                    @endif

                    @if( $product->isCrueltyFree() )
                        <div class="label vegan">Cruelty Free</div>
                    @endif

                    <ul class="product__hover">
                        <li><a href="{{ url( $product->slug ) }}" data-toggle="tooltip" data-placement="bottom" title="Ver produto"><span class="fa fa-eye"></span></a></li>
                        <li>
                            <a onclick="addToWishlist( {{ $product->id }}, {{ $uid }} );" data-toggle="tooltip" data-placement="bottom" title="Adicionar à minha Wishlist">
                                <span class="fa fa-heart-o"></span>
                            </a>
                        </li>
                        @if( $product->quantity > 0 )
                            <li>
                                <a onclick="addToCart(this);" data-toggle="tooltip" data-placement="bottom" title="Adicionar ao carrinho">
                                    <span class="fa fa-shopping-cart"></span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="product__item__text">
                    <h6><a href="{{ url($product->slug) }}">{{ $product->name }}</a></h6>
                    @if( request()->path() != 'lojas/' . $product->shop->slug )
                        <p>{{ $product->shop->name }}</p>
                    @endif
                    @if( $product->quantity <= 0 )
                        <div class="product__price text-muted">produto indisponível</div>
                    @else
                        <div class="product__price">{{ fmoney( $product->price() ) }}</div>
                    @endif
                </div>

                <form method="POST" action="{{ url('/carts') }}" accept-charset="utf-8" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                </form>
            </div>
        </div>
    @endforeach
@else
    <div class="col none">
        Nenhum produto encontrado.
    </div>
@endif