<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Order;
use App\Models\Store\StoreRating;
use App\Models\Store\Store;

class RatingsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = StoreRating::class;
        $this->route        = '/avalie';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * There is no list, redirect to Store Rating form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if( auth()->guest() )
            return redirect('/entrar?t=avalie');

        // Get Store ID
        $store = $request->input('store');

        return redirect('/store-ratings/create?store=' . $store);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Get Store & User
        $store_id = $request->input('store');
        $store    = Store::find( $store_id );
        $user     = auth()->user();

        if( ! $store )
            return redirect('/');

        // Check if User already has rating this Store
        $rating = StoreRating::where('store_id', '=', $store_id)->where('user_id', '=', $user->id)->first();

        if( $rating )
            return redirect('/store-ratings/' . $rating->id);
        
        // Build view data
        $data = $this->commonViewData( 'Keewe | Avalie a loja', $store->name );

        $data['store'] = $store;
        $data['can_rating'] = $this->gotSomething( $user->id, $store_id );

        //dd( $data );

        return $this->page( 'store.store-rating-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Get individual ratings
        $service     = intval( $request->input('service') );
        $products    = intval( $request->input('products') );
        $shipping    = intval( $request->input('shipping') );
        $after_sales = intval( $request->input('after_sales') );

        $average = round(($service + $products + $shipping + $after_sales) / 4);

        // Merge average
        $request->merge(['average' => $average]);

        // Resolve model
        $this->resolveModel();

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Next page
        $next = $request->input('referer') ?? '/store-ratings/' . $this->model->id;

        $data = [ 'next_page' => url( $next ) ];

        // Return
        return $this->responseSuccess( 'Agradecemos pela sua avaliação!', $data );
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
        // Load Rating
        $rating = StoreRating::find( $id );
        $store  = Store::find( $rating->store_id );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Avalie a loja', $store->name );

        $data['store'] = $store;
        $data['rating'] = $rating;

        return $this->page( 'store.store-rating-view', $data );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
    /**
     * Indicates if User already have purchased some item at given Store
     *
     * @param int $user_id
     * @param int $store_id
     * @return bool
     */
    protected function gotSomething( $user_id, $store_id )
    {
        $fields = [
            'orders.id', 'orders.tid', 'orders.status', 'products.id AS product_id', 'products.name as product', 
            'order_items.quantity', 'order_items.total_price', 'orders.created_at'
        ];

        $orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.user_id', '=', $user_id)
                ->where('products.store_id', '=', $store_id)
                ->select( $fields )
                ->get();
            
        if( $orders && count($orders) > 0 )
        {
            // User already have purchased something with this Store
            return true;
        }

        return false;
    }
}
