<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

class MainController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = null;
        $this->route        = '';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Slugify handler
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function slugify( Request $request )
    {
        $data = [
            'result' => slug( $request->input('input') )
        ];

        return $this->responseSuccess( null, $data );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
