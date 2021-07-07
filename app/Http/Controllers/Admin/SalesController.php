<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\Store\Store;
use App\Models\Store\StoreInfo;
use App\Models\Common\Image;

use App\Services\Payments\Pagarme;

class SalesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Order::class;
        $this->route        = '/vendas';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the Orders list
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

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', 'Vendas' );

        // Get dashboard data
        $data['store']  = $store;
        $data['orders'] = $this->loadSales( $store->id );

        return $this->page( 'seller.sales', $data );
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
    /**
     * Returns the data for Seller Dashboard as JSON
     *
     * @param Request $request
     * @return Response
     */
    public function chartData( Request $request )
    {
        // Get Store ID
        $id   = $request->id;
        $year = date('Y');

        $selects = [];
        $query = '';

        for( $i = 1; $i <= 12; $i++ )
        {
            $month = str_pad( $i, 2, '0', STR_PAD_LEFT );
            $date  = $month .'/'. $year;

            $query = "SELECT '{$date}' AS `month`, SUM(order_items.total_price) AS `revenue` "
                   . "FROM orders "
                   . "INNER JOIN order_items ON orders.id = order_items.order_id "
                   . "INNER JOIN products ON order_items.product_id = products.id "
                   . "INNER JOIN stores ON products.store_id = stores.id "
                   . "WHERE stores.id = {$id} "
                   . "AND MONTH(orders.created_at) = {$i} AND YEAR(orders.created_at) = {$year}";


            // Add to selects
            $selects[] = $query;
        }

        // Union All
        $union = implode(' UNION ALL ', $selects);
        $result = DB::select( $union );

        //dd( $union );
        //dd( $result );

        $data = [ 'success' => true, 'data' => $result ];

        return response()->json( $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Loads and returns the Store Sales after Shipping
     *
     * @param int $store_id
     * @return array
     */
    protected function loadSales( $store_id )
    {
        // Fields
        $fields = [
            'orders.id', 'orders.tid', 'users.fullname', 'order_items.product_id', 'products.name', 'images.url',
            'order_items.quantity', 'order_items.unit_price', 'order_items.total_price',
            'orders.created_at', 'orders.status'
        ];

        $list = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
                ->leftJoin('images', 'product_images.image_id', '=', 'images.id')
                ->where('products.store_id', '=', $store_id)
                ->where('product_images.position', '=', 1)
                ->where('orders.status', '=', 'delivered')
                ->orderBy('orders.created_at', 'desc')
                ->select( $fields )
                ->paginate(20);

        return $list;
    }
}
