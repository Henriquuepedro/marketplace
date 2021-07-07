<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Store;
use App\Models\Store\StoreInfo;
use App\Models\Common\Image;

use App\Services\Payments\Pagarme;

class StoresController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Store::class;
        $this->route        = '/stores';

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
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        // Start Stores query
        $query = Store::withCount('products');

        // Check for filters
        if( $request->has('status') && ($request->status === 'inactive') )
        {
            $query->inactive();
        }
        else
        {
            $query->active();
        }

        // Get Store list
        $data['stores'] = $query->paginate(20);

        //dd( $data );

        return $this->page( 'master.stores', $data );
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
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['store'] = Store::withCount('products')->find( $id );

        return $this->page( 'master.store-view', $data );
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
     * Updates Store status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus( Request $request )
    {
        // Load Store
        $store = Store::find( $request->id );

        if( ! $store )
            return \redirect('/stores');

        // Get action
        $action = $request->action;

        // Change status
        switch( $action )
        {
            case 'block':
                $store->inactivate();
                break;
            case 'unblock':
                $store->activate();
                break;
        }

        return $this->responseSuccess('Operação concluída com sucesso!');
    }

    // PROTECTED METHODS ==========================================================================
}
