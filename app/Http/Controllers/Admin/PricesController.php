<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Store;
use App\Models\Store\Product;


class PricesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Product::class;
        $this->route        = '/precos';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Resource entry point
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Always redirect to create
        return redirect('/precos/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Get Category, if given
        $category = $request->input('category');
        //$percent  = to_float( $request->input('percent') );
        $products = [];

        // Get values
        $pct = to_float( $request->input('pct') );
        $val = to_float( $request->input('val') );
        $fix = to_float( $request->input('fix') );
        $op  = $request->input('op');

        //dd( $pct, $val, $fix, $op );

        if( $category )
        {
            $items = Product::join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('products.store_id', '=', $store->id)
                    ->where('product_categories.category_id', '=', $category)
                    ->orderBy('products.name')
                    ->select('products.*')
                    ->get();

            //dd( json_decode( json_encode( $items ) ) );

            foreach( $items as $item )
            {
                $price     = to_float( $item->price );
                $new_price = $price;

                // Percent
                if( $pct > 0 )
                {
                    $relative  = (($op == 'increase') ? (100.00 + $pct) : (100.00 - $pct));
                    $new_price = (( $price * $relative ) / 100);
                }
                else if( $val > 0 )
                {
                    $new_price = (($op == 'increase') ? ($price + $val) : ($price - $val));
                }
                else if( $fix > 0 )
                {
                    $new_price = $fix;
                }

                $products[] = (object)[
                    'id' => $item->id,
                    'image' => $item->mainImageUrl(),
                    'name' => $item->name,
                    'price' => $price,
                    'new_price' => $new_price
                ];
            }
        }

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        $data['form_action'] = url('/precos');
        $data['action']      = 'create';
        $data['store']       = $store;
        $data['category']    = $category;
        $data['pct']         = (($pct > 0) ? $pct : '');
        $data['val']         = (($val > 0) ? $val : '');
        $data['fix']         = (($fix > 0) ? $fix : '');
        $data['op']          = $op;
        $data['products']    = $products;

        return $this->page( 'seller.prices-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // First, get selected items
        $items = $request->input('items');

        if( ! $items )
        {
            return $this->responseError( 'Por favor, selecione um ou mais produtos' );
        }

        // Get prices
        $prices = $request->input('prices');

        foreach( $items as $key => $id )
        {
            $product = Product::find( $id );

            $product->price = to_float( $prices[$id] );
            $product->save();
        }

        // Data to be returned
        $data = [ 'next_page' => url('/precos/create') ];

        return $this->responseSuccess( 'Os preços foram reajustados com sucesso!', $data );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Request $request, $id )
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
    {
        //
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
