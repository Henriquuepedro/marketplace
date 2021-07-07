<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Services\Shipping\ShippingService;

class ShippingController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name = '';
        $this->route      = '/shipping';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    // OTHER PUBLIC METHODS =======================================================================
    /**
     * Calculates the Shipping price from Cart
     *
     * @param Request $request
     * @return Response
     */
    public function calculate( Request $request )
    {
        //
        ShippingService::calculate();

        // Data to be returned
        $data = ['next_page' => url('/carrinho')];

        return $this->responseSuccess( null, $data );
    }

    // PROTECTED METHODS ==========================================================================
}
