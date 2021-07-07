<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Store\Order;
use App\Models\Store\OrderOccurrence;
use App\Models\Store\Product;

use App\Services\MailService;

class OrdersController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Order::class;
        $this->route        = '/meus-pedidos';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // User & View data
        $user = auth()->user();
		$data = $this->commonViewData( 'Keewe | Minha conta', 'Meus pedidos' );

        $data['me'] = $user;
        $data['orders'] = $this->getMyOrders( $user->id );

        //dd($data);

        return $this->page( 'user.orders', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //
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
        // Resolve model
        $this->resolveModel($id);

        // User & View data
        $user  = auth()->user();
		$data  = $this->commonViewData( 'Keewe | Minha conta', 'Meus pedidos' );
        $order = $this->model;

        $data['me'] = $user;
        $data['order'] = $order;
        $data['json']  = json_decode( $order->request_data );
        $data['total'] = $order->amount;
        $data['form_action'] = url('/meus-pedidos/' . $id);
        $data['action'] = 'update';

        // Check for Occurrence
        $data['occurrence'] = OrderOccurrence::where('order_id', '=', $order->id)->where('user_id', '=', $user->id)->first();

        //dd($data);

        return $this->page( 'user.order', $data );
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
        // Get input
        $input = $request->all();
        $item  = Product::where( 'id', '=', $input['product_id'] )->first();

        if( ! $item )
            return $this->responseError('Por favor, selecione um produto.');

        // Resolve some vars
        $input['order_id'] = $id;
        $input['store_id'] = $item->store_id;

        //dd( $input );

        // Resolve model & Rules
        $model = resolve( OrderOccurrence::class );
        $rules = OrderOccurrence::RULES_STORE;

		//dd( $rules );

		// Validates input
        $this->validateInput( $input, $rules );

        //dd( $input );

        // Store data
        $occurrence = new OrderOccurrence();

        $occurrence->order_id    = (int) $input['order_id'];
        $occurrence->user_id     = (int) $input['user_id'];
        $occurrence->store_id    = (int) $input['store_id'];
        $occurrence->product_id  = (int) $input['product_id'];
        $occurrence->reason      = $input['reason'];
        $occurrence->description = $input['description'];

        $occurrence->save();

        // Send mail to vendor
        $this->sendOccurrenceMail( $occurrence );

        // Data to be returned
        $data = [
            'next_page' => url('meus-pedidos')
        ];

        return $this->responseSuccess('Sua solicitação foi encaminhada para o responsável.', $data);
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
    /**
     * Return my Orders
     *
     * @param integer $uid
     * @return array|Collection
     */
    protected function getMyOrders( $uid )
    {
        return Order::where('user_id', '=', $uid)->orderBy('updated_at', 'desc')->paginate(10);
    }

    /**
     * Sends occurrence to vendor by mail
     *
     * @param OrderOccurrence $occurrence
     * @return void
     */
    protected function sendOccurrenceMail( $occurrence )
    {
        // Get Store, User & Order
        $store    = $occurrence->shop;
        $customer = $occurrence->user;
        $order    = $occurrence->order;
        $item     = $order->items()->where('product_id', '=', $occurrence->product_id)->first();

        //dd( $store );
        //dd( $customer );
        //dd( $order );
        //dd( $item );

        $event = (object)[
            'customer' => $customer->fullname,
            'email' => $customer->username,
            'item' => $item->name,
            'reason' => __( 'messages.occurrences.' . $occurrence->reason ),
            'description' => $occurrence->description
        ];

        //dd( $event );

        $data = (object)[
            'fullname' => $store->owner->fullname,
            'order_tid' => $order->tid,
            'occurrence' => $event
        ];

        MailService::sendOrderOccurrence( $store->owner->username, $data );
    }
}
