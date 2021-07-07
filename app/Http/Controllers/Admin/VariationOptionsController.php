<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\VariationOption;

class VariationOptionsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = VariationOption::class;
        $this->route        = '/var-options';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * This resource does not have listing
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        //
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
        // Resolve model
        $this->resolveModel();

        // Fix decimal
        $this->fixNumbers( $request );

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Return to Variations
        $data = [
            'next_page' => url( '/variacoes/'. $this->model->variation_id .'/edit' )
        ];

        return $this->responseSuccess( 'Opção adicionada, por favor, aguarde...', $data );
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
        // Resolve model
        $this->resolveModel($id);

        // Fix decimal
        $this->fixNumbers( $request );

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'update' );

        // Return to Variations
        $data = [
            'next_page' => url( '/variacoes/'. $this->model->variation_id .'/edit' )
        ];

        return $this->responseSuccess( 'Opção atualizada, por favor, aguarde...', $data );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
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
            'next_page' => url( '/variacoes/'. $variation_id .'/edit' )
        ];

        return $this->responseSuccess( 'Opção removida, por favor, aguarde...', $data );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
    /**
     * Fix numbers
     *
     * @param Request $request
     * @return void
     */
    protected function fixNumbers( Request $request )
    {
        $price = $request->price;
        $price = to_float( $price );

        $request->merge( ['price' => $price] );
    }

}
