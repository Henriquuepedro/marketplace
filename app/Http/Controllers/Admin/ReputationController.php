<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\StoreRating;

class ReputationController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = StoreRating::class;
        $this->route        = '/reputacao';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the resource index
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

        // Store & Questions
        $data['store']   = $store;
        $data['ratings'] = $this->getRatings( $store->id );

        //dd( $data );

        return $this->page( 'seller.reputation', $data );
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
        //return parent::destroy( $request, $id );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns Store Ratings of given Store
     *
     * @param int $store_id
     * @return array
     */
    protected function getRatings( $store_id )
    {
        // Fields
        $fields = 'IFNULL(AVG(average), 0) AS average, '
                . 'IFNULL(AVG(service), 0) AS service, IFNULL(((AVG(service) * 100) / 5), 0) AS service_pct, '
                . 'IFNULL(AVG(products), 0) AS products, IFNULL(((AVG(products) * 100) / 5), 0) AS products_pct, '
                . 'IFNULL(AVG(shipping), 0) AS shipping, IFNULL(((AVG(shipping) * 100) / 5), 0) AS shipping_pct, '
                . 'IFNULL(AVG(after_sales), 0) AS after_sales, IFNULL(((AVG(after_sales) * 100) / 5), 0) AS after_sales_pct ';

        // Query
        $query  = StoreRating::where('store_id', '=', $store_id)->selectRaw( $fields )->get();
        $result = json_decode( json_encode( $query ) );

        if( is_array($result) )
            $result = $result[0];

        //dd( $result );

        foreach( $result as $key => $val )
        {
            $result->{$key} = (float) $val;
        }

        return $result;
    }
}
