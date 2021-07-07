<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Store;
use App\Models\Store\Product;


class InventoryController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Product::class;
        $this->route        = '/estoque';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Inventory entry point
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

        // Get Action
        $act = $request->input('act');

        switch( $act )
        {
            case 'plus':
                return redirect('/estoque/create');
                break;
            case 'minus':
                return redirect('/estoque/0/edit');
                break;
        }
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

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        $data['form_action'] = url('/estoque');
        $data['action']      = 'create';
        $data['store']       = $store;
        $data['products']    = Product::where('store_id', '=', $store->id)->where('quantity', '=', 0)->orderBy('name')->get();

        return $this->page( 'seller.inventory-add', $data );
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

        // Get quantities
        $quantities = $request->input('qtd');

        foreach( $items as $key => $id )
        {
            $product = Product::find( $id );

            $product->quantity = $quantities[$id];
            $product->save();
        }

        // Data to be returned
        $data = [ 'next_page' => url('/estoque/create') ];

        return $this->responseSuccess( 'Produto(s) atualizado(s) com sucesso!', $data );
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
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get Category and percent, if given
        $category = $request->input('category');
        $products = [];

        //dd( $percent );

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
                $products[] = (object)[
                    'id' => $item->id,
                    'image' => $item->mainImageUrl(),
                    'name' => $item->name,
                    'price' => to_float($item->price),
                    'quantity' => $item->quantity
                ];
            }
        }

        //dd( $products );

        // Get dashboard data
        $data['form_action'] = url('/estoque/0');
        $data['action']      = 'update';
        $data['store']       = $store;
        $data['category']    = $category;
        $data['products']    = $products;

        return $this->page( 'seller.inventory-rem', $data );
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

        // Remove products from inventory
        Product::whereIn('id', $items)->update(['quantity' => 0]);

        // Data to be returned
        $data = [ 'next_page' => url('/estoque?act=minus') ];

        return $this->responseSuccess( 'Os produtos foram esgotados com sucesso!', $data );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
    {
        return parent::destroy( $request, $id );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
