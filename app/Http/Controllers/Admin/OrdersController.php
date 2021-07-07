<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\Store\Store;
use App\Models\Store\StoreInfo;
use App\Models\Common\Image;

use App\Services\Payments\Pagarme;
use App\Services\MailService;

class OrdersController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Order::class;
        $this->route        = '/pedidos';

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
        $data = $this->commonViewData( 'Keewe | Minha Loja', 'Pedidos' );

        // Get dashboard data
        $data['store']  = $store;
        $data['orders'] = $this->loadStoreOrders( $store->id );

        return $this->page( 'seller.orders', $data );
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

        // Load Order
        $order = Order::find( $id );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', 'Pedido #' . $order->tid );

        // Calculates the Total And Shipping prices
        $total    = 0.00;
        $shipping = 0.00;

        foreach( $order->items as $item )
        {
            if( $item->product->store_id != $store->id )
                continue;

            $total += $item->total_price;
        }

        // Get data
        $data['store'] = $store;
        $data['order'] = $order;
        $data['json']  = json_decode( $order->request_data );
        $data['total'] = $total;
        $data['form_action'] = url('/pedidos/' . $id);
        $data['action'] = 'update';

        //dd( $data['json'] );

        // Audit
        //$this->retrievedAudit( $this->model_name, $id, 'showed' );

        return $this->page( 'seller.order-edit', $data );
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
        // Load Order
        $order = Order::find($id);

        // Get status
        $status = $request->status;

        switch( $status )
        {
            case 'in_transit':
                $order->tracking_code = $request->tracking_code;
                $order->shipping_date = now();
                break;
            case 'delivered':
                $order->delivery_date = now();
                break;
        }

        // Update data
        $order->status = $status;
        $order->save();

        // Get customer
        $customer = $order->client;

        // Send Mail
        MailService::sendOrderShipped( $customer->fullname, $customer->username, $order );

        return $this->responseSuccess('O pedido foi atualizado.', ['next_page' => url('/pedidos')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
    {
        //return parent::destroy( $request, $id );

        // Resolve model
        $this->resolveModel($id);

        $this->model->status = 'refused';
        $this->model->refuse_reason = 'seller';
        $this->model->status_reason = 'seller';
        $this->model->save();

        // Return
        return $this->responseSuccess( 'O item foi excluído com sucesso!' );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
    protected function loadStoreOrders( $store_id )
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
                ->where('orders.status', '!=', 'delivered')
                ->orderBy('orders.created_at', 'desc')
                ->select( $fields )
                ->paginate(20);

        return $list;
    }
}
