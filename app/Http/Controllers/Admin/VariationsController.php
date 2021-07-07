<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\ProductVariation;
use App\Models\Store\VariationOption;
use App\Models\Store\Product;

class VariationsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = ProductVariation::class;
        $this->route        = '/variacoes';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the Product Variations list
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

        // Store data & Products
        $data['store'] = $store;
        $data['variations'] = ProductVariation::where('store_id', '=', $store->id)->active()->orderBy('product_id')->get();

        return $this->page( 'seller.variations-list', $data );
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

        // Get dashboard data
        $data['form_action'] = url('/variacoes');
        $data['action']      = 'create';
        $data['store']       = $store;
        $data['products']    = $this->productsForSelect( $store->id );

        return $this->page( 'seller.variations-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Resolve model
        $this->resolveModel();

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Return
        $data = [
            'next_page' => url( $this->route .'/'. $this->model->id .'/edit' )
        ];

        return $this->responseSuccess( 'Variação cadastrada com sucesso! Por favor, aguarde...', $data );
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

        // Resolve model
        $this->resolveModel( $id );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get dashboard data
        $data['form_action'] = url('/variacoes/' . $id);
        $data['action']      = 'update';
        $data['store']       = $store;
        $data['variation']   = $this->model;
        $data['products']    = $this->productsForSelect( $store->id );

        return $this->page( 'seller.variations-form', $data );
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

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'update' );

        // Return to list
        $data = [
            'next_page' => url( $this->route )
        ];

        return $this->responseSuccess( 'Variação atualizada com sucesso!', $data );
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
     * Add an Option
     *
     * @param Request $request
     * @return Response
     */
    public function addOption( Request $request )
    {
        // Validate input
        $this->validateInput( $request->all(), VariationOption::RULES_STORE );

        $option = new VariationOption();

        $option->variation_id = $request->input('variation_id');
        $option->name         = $request->input('name');
        $option->position     = $request->input('position');
        $option->quantity     = $request->input('quantity') ?? 0;
        $option->price        = $request->input('price');

        $option->save();

        // Return
        $data = [
            'next_page' => url( $this->route .'/'. $option->variation_id .'/edit' )
        ];

        return $this->responseSuccess( 'Opção adicionada com sucesso! Por favor, aguarde...', $data );
    }

    /**
     * Removes an Option
     *
     * @param Request $request
     * @return Response
     */
    public function removeOption( Request $request )
    {
        // Get data
        $id = $request->input('id');
        $variation_id = $request->input('variation_id');

        if( ! $id )
            return $this->responseError('Ocorreu uma falha na operação. Por favor, tente novamente.');

        // Delete the record
        VariationOption::destroy( $id );

        // Return
        $data = [
            'next_page' => url( $this->route .'/'. $variation_id .'/edit' )
        ];

        return $this->responseSuccess( 'Opção removida com sucesso! Por favor, aguarde...', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Load Products to be showed in a Select element
     *
     * @param int $store_id
     * @return array
     */
    protected function productsForSelect( $store_id )
    {
        $list = Product::where('store_id', '=', $store_id)
                ->where('status', '=', 'active')
                ->select(['id as value', 'name as label'])
                ->get();

        return $list;
    }
}
