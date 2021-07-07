<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Coupon;
use App\Models\Store\CouponProduct;

class CouponsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Coupon::class;
        $this->route        = '/cupons';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Resource listing
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
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        $data['store']   = $store;
        $data['coupons'] = Coupon::where('status', '!=', 'deleted')->get();

        return $this->page( 'seller.coupon-list', $data );
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

        $data['form_action'] = url('/cupons');
        $data['action']      = 'create';
        $data['store']       = $store;
        $data['cbo_types']   = $this->getDiscountTypes();
        $data['products']    = $store->products;

        return $this->page( 'seller.coupon-add', $data );
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
        $items = $request->input('products');

        if( ! $items )
        {
            return $this->responseError( 'Por favor, selecione um ou mais produtos' );
        }

        // Fix numbers
        $this->fixValues( $request );

        // Fix Code
        $request->merge(['code' => strtoupper($request->code)]);

        // Resolve model
        $this->resolveModel();

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Link products
        foreach( $items as $product_id )
        {
            CouponProduct::create(['coupon_id' => $this->model->id, 'product_id' => $product_id]);
        }

        // Data to be returned
        $data = [ 'next_page' => url('/cupons') ];

        return $this->responseSuccess( 'Cupom de desconto criado com sucesso!', $data );
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

        $data['form_action'] = url('/cupons/' . $id);
        $data['action']      = 'update';
        $data['store']       = $store;
        $data['coupon']      = Coupon::find($id);
        //$data['cbo_types']   = $this->getDiscountTypes();
        //$data['products']    = $store->products;

        return $this->page( 'seller.coupon-edit', $data );
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

        // Load status & coupon
        $status = $request->input('status');
        $this->resolveModel($id);

        // Desactivate it
        $this->model->status = $status;
        $this->model->save();

        // Message
        $msg = (($status == 'active') ? ' ativado e está ' : ' desativado e não está mais ');

        // Data to be returned
        $data = [ 'next_page' => url('/cupons') ];

        return $this->responseSuccess( 'O cupom foi '. $msg .' disponível para uso', $data );
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
     * Returns the options for Discount Type select
     *
     * @return string
     */
    protected function getDiscountTypes()
    {
        $types = [
            (object)['value' => 'none',             'label' => 'Nenhum desconto'],
            (object)['value' => 'products_amount',  'label' => 'Valor fixo na soma dos produtos'],
            (object)['value' => 'total_amount',     'label' => 'Valor fixo no total do pedido'],
            (object)['value' => 'shipping_amount',  'label' => 'Valor fixo no frete'],
            (object)['value' => 'products_percent', 'label' => 'Porcentagem na soma dos produtos'],
            (object)['value' => 'total_percent',    'label' => 'Porcentagem no total do pedido'],
            (object)['value' => 'shipping_pecent',  'label' => 'Porcentagem no frete'],
        ];

        return json_encode( $types );
    }

    /**
     * Fix numeric values
     *
     * @param Request $request
     * @return void
     */
    protected function fixValues( Request $request )
    {
        $inputs = [
            //'limit'           => $request->input('limit'),
            'discount_value'  => $request->input('discount_value'),
            'min_order_value' => $request->input('min_order_value'),
        ];

        foreach( $inputs as &$inp )
        {
            $inp = to_float( $inp );
        }

        $request->merge( $inputs );
    }
}
