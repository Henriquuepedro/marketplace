<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

class DashboardController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = null;
        $this->route        = '/dashboard';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Shows the Dashboard page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        // Get dashboard data
        //$data['store'] = $store;

        return $this->page( 'master.dashboard', $data );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
