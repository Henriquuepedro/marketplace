<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Location\Address;
use App\Models\Store\Store;
use App\Models\Store\StoreInfo;
use App\Models\Common\Image;
use App\Models\Store\Order;
use App\Models\Store\Question;

use App\Services\Payments\Pagarme;

class StoreController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Store::class;
        $this->route        = '/minha-loja';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * In this case (admin), display the User's Store
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

        // Get dashboard data
        $data['store'] = $store;
        $data['revenues'] = $this->getRevenues( $store->id );
        $data['last_orders'] = $this->loadLastOrders( $store->id, 10 );
        $data['questions'] = $this->getUnansweredQuestionAmount( $store->id );

        return $this->page( 'seller.dashboard', $data );
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
        $store = $this->checkStore( $id );

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get data
        $data['store'] = $store;

        // Audit
        //$this->retrievedAudit( $this->model_name, $id, 'showed' );

        return $this->page( 'seller.store-edit', $data );
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
        // Re-generate slug and check it
        $slug = slug( $request->name );

        $exists = Store::where('slug', '=', $slug)->where('id', '!=', $id)->first();

        if( $exists )
        {
            return $this->responseError( 'O nome da loja está sendo utilizado. Por favor, escolha um outro nome.' );
        }

        // Merge slug
        $request->merge(['slug' => $slug]);

        // Check Address
        $address_id = $request->address_id;

        $address = empty($address_id) ? new Address() : Address::find( $address_id );

        // Fill and save address
        $address->fill([
            'type'         => $request->type,
            'address'      => $request->address,
            'number'       => $request->number,
            'complement'   => $request->complement,
            'neighborhood' => $request->neighborhood,
            'city'         => $request->city,
            'zipcode'      => $request->zipcode,
            'state_id'     => $request->state_id,
            'country_id'   => $request->country_id
        ]);

        $address->save();

        $request->merge(['address_id' => $address->id]);

        return parent::update( $request, $id );
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
    /**
     * Shows the Store Info page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showBankPage( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get data
        $data['store'] = $store;
        $data['info']  = $store->info;

        // Bank account types
        $acc_types = [
            (object)['value' => 'conta_corrente', 'label' => 'Conta Corrente'],
            (object)['value' => 'conta_poupanca', 'label' => 'Conta Poupança'],
            (object)['value' => 'conta_corrente_conjunta', 'label' => 'Conta Corrente conjunta'],
            (object)['value' => 'conta_poupanca_conjunta', 'label' => 'Conta Poupanca conjunta'],
        ];

        $data['acc_types'] = json_encode( $acc_types );

        // Audit
        //$this->retrievedAudit( $this->model_name, $id, 'showed' );

        //dd( $data );

        return $this->page( 'seller.store-info', $data );
    }

    /**
     * Saves the Store Info page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveBank( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Get Store Info ID & Action
        $id     = $request->input('id', null);
        $action = ( is_null($id) ? 'store' : 'update' );

        // Change model to StoreInfo temporarily
        $this->model_name = StoreInfo::class;

        $this->resolveModel( $id );

        // Validates and Saves Store Info
        $this->validateAndSave( $request->all(), $action );

        // Check Gateway specific fields
        if( is_null($this->model->recipient_id) )
        {
            // Create bank account and recipient
            $gateway = Pagarme::addRecipient( $this->model );

            if( $gateway->success === true )
            {
                $this->model->bank_account_id  = $gateway->bank_account_id;
                $this->model->recipient_id     = $gateway->recipient_id;
                $this->model->recipient_status = $gateway->status;
                $this->model->status_reason    = $gateway->reason;

                $this->model->save();
            }
        }

        // Next page
        $data = [
            'next_page' => url('/minha-loja/bank')
        ];

        return $this->responseSuccess( 'Informações gravadas com sucesso.', $data );
    }


    /**
     * Show the form for store configuration
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showConfig( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get data
        $data['store'] = $store;

        // Available backgrounds
        $data['backgrounds'] = Image::where('path', '=', 'media/backgrounds')->get();

        // Audit
        //$this->retrievedAudit( $this->model_name, $id, 'showed' );

        //dd( $store->cover );

        return $this->page( 'seller.config', $data );
    }

    /**
     * Save Store images
     *
     * @param Request $request
     * @return void
     */
    public function setImage( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Get type and Image
        $type   = $request->type;
        $img_id = $request->imgid;

        if( ! $img_id )
            return $this->responseError( 'Ocorreu um erro ao recuperar a imagem. Por favor, tente novamente.' );

        // Update Store
        switch( $type )
        {
            case 'background':
                $store->background_id = $img_id;
                break;
            case 'cover':
                $store->cover_id = $img_id;
                break;
            case 'logo':
                $store->logo_id = $img_id;
                break;
        }

        // Save
        $store->save();

        return $this->responseSuccess(  );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns the revenues of given Store ID
     *
     * @param int $store_id
     * @return array
     */
    protected function getRevenues( $store_id )
    {
        $query = "SELECT stores.id AS store_id, stores.name AS store, "
               . "DATE_FORMAT(orders.created_at, '%m.%Y') AS `month`, "
               . "SUM(order_items.quantity) AS `quantity`, "
               . "SUM(order_items.total_price) AS `revenue` "
               . "FROM orders "
               . "INNER JOIN order_items ON orders.id = order_items.order_id "
               . "INNER JOIN products ON order_items.product_id = products.id "
               . "INNER JOIN stores ON products.store_id = stores.id "
               . "WHERE (MONTH(orders.created_at) >= MONTH(NOW()) - 1) "
               . "AND (stores.id = {$store_id}) "
               . "GROUP BY stores.id, DATE_FORMAT(orders.created_at, '%m.%Y') "
               . "ORDER BY DATE_FORMAT(orders.created_at, '%m.%Y') DESC";

        // Execute
        $result = \DB::select( $query );

        // Vars
        $revenues = [];
        $total    = 0.00;

        //dd( $result );

        foreach( $result as $entry )
        {
            $total += floatval( $entry->revenue );

            $revenues[] = (object)[
                'month' => $entry->month,
                'revenue' => floatval($entry->revenue)
            ];
        }

        // Add the total as first array element
        array_unshift( $revenues, (object)['month' => 'all', 'revenue' => $total] );

        //dd( $revenues );

        return $revenues;
    }

    /**
     * Returns the Last Orders for Dashboard
     *
     * @param int $store_id
     * @param int $quantity
     * @return array
     */
    protected function loadLastOrders( $store_id, $quantity )
    {
        // Fields
        $fields = [
            'orders.id', 'orders.tid', 'users.fullname', 'order_items.product_id', 'products.name',
            'order_items.quantity', 'order_items.unit_price', 'order_items.total_price',
            'orders.created_at', 'orders.status'
        ];

        $list = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('products.store_id', '=', $store_id)
                //->where('orders.status', '!=', 'delivered')
                ->orderBy('orders.created_at', 'desc')
                ->select( $fields )
                ->limit( $quantity )
                ->get();

        return $list;
    }

    /**
     * Returns the number of Unanswered Questions for Dashboard
     *
     * @param int $store_id
     * @return int
     */
    protected function getUnansweredQuestionAmount( $store_id )
    {
        $amount = Question::where('store_id', '=', $store_id)
                ->whereNull('answer')
                ->count();

        return $amount;
    }
}
