<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Location\Address;
use App\Models\Auth\UserAddress;

class AddressesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Address::class;
        $this->route        = '/addresses';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the User's addresses list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Alias to User
        $user = auth()->user();

        // Check request
        if( request()->is('cobranca') )
        {
            $title = 'endereço de cobrança';
            $type  = 'billing';
        }
        else if( request()->is('entrega') )
        {
            $title = 'endereço de entrega';
            $type  = 'shipping';
        }

        // List fields
        $fields = [
            'addresses.id', 'addresses.address', 'addresses.number', 'addresses.city', 'addresses.created_at as simple_date'
        ];

        // Get list
        $list = $user->addresses()->where('type', '=', $type)->select( $fields )->get();

        // View data
        $data = $this->commonViewData( 'Keewe | Minha conta', $title );

        $data['addresses'] = $list;
        $data['type'] = $type;

        return $this->page( 'user.addresses', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Get type
        $type  = $request->type;
        $title = 'endereço de ' . ( $type === 'billing' ? 'cobrança' : 'entrega' );

         // View data
        $data = $this->commonViewData( 'Keewe | Minha conta', $title );

        $data['form_action'] = url('/addresses');
        $data['action']      = 'create';
        $data['type']        = $type;
        $data['user']        = auth()->user();

        return $this->page( 'user.addresses-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Validates and Saves Address
        $this->validateAndSave( $request->all(), 'store' );

        // Get Address ID
        $address_id = $this->model->id;

        // Check if User ID has been passed
        if( $request->has('user_id') )
        {
            // Link Address to User
            UserAddress::create([
                'user_id'    => $request->user_id,
                'address_id' => $address_id
            ]);
        }

        // Return if not in User Admin area
        if( $request->origin != 'adm' )
        {
            return $this->responseSuccess();
        }

        // Type
        $type = $request->type;

        $data = [
            'next_page' => ($type === 'billing') ? url('/cobranca') : url('/entrega')
        ];

        return $this->responseSuccess( 'Endereço adicionado', $data );
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
        // Load data
        $this->resolveModel( $id );

         // View data
        $data = $this->commonViewData( 'Keewe | Minha conta', 'atualizar endereço' );

        $data['form_action'] = url('/addresses/' . $id);
        $data['action']      = 'update';
        $data['address']     = $this->model;
        $data['type']        = $this->model->type;
        $data['user']        = auth()->user();

        //dd( $data );

        return $this->page( 'user.addresses-form', $data );
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
        // Resolve model
        $this->resolveModel($id);

        // Validates and Saves Address
        $this->validateAndSave( $request->all(), 'update' );

        // Return if not in User Admin area
        if( $request->origin != 'adm' )
        {
            return $this->responseSuccess();
        }

        // Type
        $type = $request->type;

        $data = [
            'next_page' => ($type === 'billing') ? url('/cobranca') : url('/entrega')
        ];

        return $this->responseSuccess( 'Seu endereço foi atualizado.', $data );
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
