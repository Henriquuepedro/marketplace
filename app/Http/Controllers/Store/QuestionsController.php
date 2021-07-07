<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Question;

class QuestionsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Question::class;
        $this->route        = '/questions';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display a resource listing
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Get product
        $product_id = $request->product_id;

        if( ! $product_id )
            return $this->responseError('Falha ao recuperar identificador do produto.');

        // Load Questions
        $q = Question::where('product_id', '=', $product_id)
                ->whereNotNull('answer')
                ->orderBy('updated_at')
                ->paginate(10);

        return $this->responseSuccess( null , ['questions' => $q]);
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
        return $this->responseSuccess( 'Sua pergunta foi encaminhada ao vendedor e será respondida em breve.' );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
