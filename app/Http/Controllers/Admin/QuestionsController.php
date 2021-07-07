<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Question;

class QuestionsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Question::class;
        $this->route        = '/perguntas';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the Question list
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
        $data['store'] = $store;
        $data['questions'] = Question::where('store_id', '=', $store->id)->orderBy('answer')->orderBy('created_at')->get();

        return $this->page( 'seller.questions-list', $data );
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

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'update' );

        // Data to be returned
        $data = [ 'next_page' => url('/perguntas') ];

        return $this->responseSuccess( null, $data );
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
}
