<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Cart;
use App\Models\Store\CartItem;
use App\Models\Store\Product;
use App\Models\Location\Address;
use App\Services\Shipping\ShippingService;

class CartController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Cart::class;
        $this->route        = '/carts';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display a Shopping Cart page with items
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Get Cart
        $zip  = $request->cep;
        /*
        $cart = get_cart();

        dump( 'start' );
        dump( $zip );

        // Check for logged user
        if( ! empty($cart->address_id) )
        {
            // Load Address
            $zip = Address::where('id', '=', $cart->address_id)->value('zipcode');
            dump( 'from cart' );
            dump( $zip );
        }
        else
        {
            // Check for User
            if( auth()->user() )
            {
                // Get first shipping User's Address
                $address = auth()->user()->addresses()->where('type', '=', 'shipping')->first();
                $zip = ($address ? $address->zipcode : null);
                dump( 'from user' );
                dump( $zip );
            }
        }

        dump( 'after checks' );
        dump( $zip );
        */

        if( $zip )
        {
            // Calculate shipping
            ShippingService::calculate();
        }

        // Load Cart
        $cart = load_cart();

        //dd( $cart );

		// Build view data
        $data = $this->commonViewData( 'Keewe | Carrinho de Compras', 'Carrinho de Compras' );

        $data['cart']    = $cart;
        $data['zipcode'] = $zip;

        //dd( $cart );
        //dd( $data );

        return $this->page( 'store.cart', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // First, validate item
        $this->validateItem();

        // Get or create a Cart
        $cart = $this->getCart();

        // Get product
        $product = Product::find( $request->product_id );

        // Get Item
        $item = CartItem::firstOrNew(
            ['cart_id' => $cart->id, 'store_id' => $product->shop->id, 'product_id' => $request->product_id]
        );

        // Quantity
        $item->quantity = (int) $item->quantity + (int) $request->quantity;
        $item->variations = json_encode($request->variation);

        // Save
        $item->save();

        // Return
        return $this->responseSuccess( 'O produto foi adicionado ao seu carrinho de compras!' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        // Get vars
        $action = $request->action;
        $cart   = get_cart();

        if( $action === 'set-address' )
        {
            $cart->address_id = $request->address;
            $cart->save();

            return $this->responseSuccess();
        }

        foreach( $cart->items as &$item )
        {
            // Skip unwanted items
            if( (int) $item->product_id != (int) $id )
                continue;

            // This is the desired item
            switch( $action )
            {
                case 'add-one':
                    $item->quantity = (int) $item->quantity + 1;
                    $item->save();
                    break;
                case 'del-one':
                    $item->quantity = (int) $item->quantity - 1;

                    if( $item->quantity === 0 )
                        $item->delete();
                    else
                        $item->save();

                    break;
                case 'del-item':
                    $item->delete();
                    break;
            }

            // Stop the loop
            break;
        }

        return $this->responseSuccess();
    }

    // OTHER PUBLIC METHODS =======================================================================
    /**
     * Returns the Cart Item count
     *
     * @param Request $request
     * @return Response
     */
    public function showCount( Request $request )
    {
        // Get Count
        $count = count_cart();

        // Data to be returned
        $data = ['count' => $count];

        return $this->responseSuccess( null, $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Validates a Cart Item
     *
     * @return void
     */
    protected function validateItem()
    {
        $item = [
            'product_id' => request()->product_id,
            'quantity' => request()->quantity
        ];
        $rules = [
            'product_id' => 'bail|required|integer|exists:products,id',
            'quantity' => 'bail|required|integer|between:1,999'
        ];

        $res = is_valid( $item, $rules );

        if( $res->isError() )
        {
            abort( $this->responseJson( $res ) );
        }
    }

    /**
     * Returns existing or new Cart
     *
     * @return Cart
     */
    protected function getCart()
    {
        return get_cart();
    }
}
