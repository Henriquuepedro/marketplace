<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Common\Page;


class PagesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Page::class;
        $this->route        = '/pages';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the Pages list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['pages'] = Page::notDeleted()->paginate(20);

        //dd( $data );

        return $this->page( 'master.pages', $data );
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
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['action'] = 'store';
        $data['form_action'] = url('/pages');

        return $this->page( 'master.page-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        return parent::store( $request );
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

        $data['page'] = Page::find($id);

        return $this->page( 'master.page-view', $data );
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
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['page']   = Page::find($id);
        $data['action'] = 'update';
        $data['form_action'] = url('/pages/'. $id);

        return $this->page( 'master.page-form', $data );
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

    // PROTECTED METHODS ==========================================================================
}
