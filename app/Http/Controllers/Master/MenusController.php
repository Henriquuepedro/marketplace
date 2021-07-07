<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Common\Menu;

class MenusController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Menu::class;
        $this->route        = '/menus';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the resource list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['menus'] = Menu::notDeleted()->paginate(20);

        //dd( $data );

        return $this->page( 'master.menus', $data );
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
        $data['form_action'] = url('/menus');
        $data['position_options'] = $this->getPositionOptions();
        $data['type_options'] = $this->getTypeOptions();

        return $this->page( 'master.menu-form', $data );
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

        $data['menu'] = Menu::find($id);
        $data['position_options'] = $this->getPositionOptions();
        $data['type_options'] = $this->getTypeOptions();

        return $this->page( 'master.menu-view', $data );
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

        $data['menu']   = Menu::find($id);
        $data['action'] = 'update';
        $data['form_action'] = url('/menus/'. $id);
        $data['position_options'] = $this->getPositionOptions();
        $data['type_options'] = $this->getTypeOptions();

        return $this->page( 'master.menu-form', $data );
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
    /**
     * Returns options for Position select
     *
     * @return string
     */
    protected function getPositionOptions()
    {
        $options = [
            (object)['value' => 'header', 'label' => 'Topo'],
            (object)['value' => 'footer', 'label' => 'Rodapé'],
        ];

        return json_encode( $options );
    }

    /**
     * Returns options for Type select
     *
     * @return string
     */
    protected function getTypeOptions()
    {
        $options = [
            (object)['value' => 'items', 'label' => 'Itens'],
            (object)['value' => 'categories', 'label' => 'Categorias'],
        ];

        return json_encode( $options );
    }
}
