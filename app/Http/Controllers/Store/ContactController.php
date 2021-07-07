<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Common\Contact;
use App\Services\MailService;

class ContactController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Contact::class;
        $this->route        = '/fale-conosco';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Build view data
        $data = $this->commonViewData( 'Keewe | Fale Conosco', 'Fale Conosco' );

        return $this->page( 'store.contact', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Save message to database
        $response = parent::store( $request );

        // Send Mail
        MailService::sendContact( $this->model );

        // Data to be returned
        $data = [ 'next_page' => url('/fale-conosco/sucesso') ];

        return $this->responseSuccess( 'Sua mensagem foi enviada.', $data );
    }

    /**
     * Shows the Store Page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function feedback( Request $request )
    {
        // Build view data
        $data = $this->commonViewData( 'Keewe | Fale Conosco', 'Fale Conosco' );

        return $this->page( 'store.contact_ok', $data );
    }
}
