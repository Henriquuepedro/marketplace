<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Store;
use App\Models\Store\Promotion;
use App\Models\Store\Product;


class PromotionController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Promotion::class;
        $this->route        = '/promocao';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Promotion entry point
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

        if( $act === 'add' )
            return redirect('/promocao/create');

        // Get promotions
        $promos = Promotion::where('status', '=', Promotion::STATUS_ACTIVE)
                ->where('store_id', '=', $store->id)
                ->get();

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        $data['form_action'] = url('/promocao/0');
        $data['action']      = 'update';
        $data['store']       = $store;
        $data['promotions']  = $promos;

        return $this->page( 'seller.promo-rem', $data );
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

        // Get Category and percent, if given
        $category = $request->input('category');
        $percent  = to_float( $request->input('percent') );
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
            //
            foreach( $items as $item )
            {
                $price     = to_float( $item->price );
                $new_price = $price;

                if( $percent > 0 )
                {
                    $relative  = 100.00 - $percent;
                    $new_price = (( $price * $relative ) / 100);
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

        //dd( $products );

        // Get dashboard data
        $data['form_action'] = url('/promocao');
        $data['action']      = 'create';
        $data['store']       = $store;
        $data['category']    = $category;
        $data['percent']     = $percent;
        $data['products']    = $products;

        return $this->page( 'seller.promo-add', $data );
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

        // Invalidate old promotions
        Promotion::whereIn('product_id', $items)->update(['status' => Promotion::STATUS_INACTIVE]);

        // Now, get new prices
        $prices = $request->input('prices');

        //dd( $prices );

        foreach( $items as $key => $id )
        {
            $promo = new Promotion();

            $promo->store_id   = $store->id;
            $promo->product_id = $id;
            $promo->price      = to_float( $prices[$id] );
            $promo->status     = Promotion::STATUS_ACTIVE;
            $promo->save();
        }

        // Data to be returned
        $data = [ 'next_page' => url('/promocao/create?category=' . $request->category) ];

        return $this->responseSuccess( 'Promoção criada com sucesso!', $data );
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

        // Invalidate old promotions
        Promotion::whereIn('product_id', $items)->update(['status' => Promotion::STATUS_INACTIVE]);

        // Data to be returned
        $data = [ 'next_page' => url('/promocao?act=rem') ];

        return $this->responseSuccess( 'Os produtos foram retirados da promoção', $data );
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
