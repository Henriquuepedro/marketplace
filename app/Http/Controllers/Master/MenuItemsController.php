<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Common\Menu;
use App\Models\Common\MenuItem;
use App\Services\NestedSetFormatter;

class MenuItemsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = MenuItem::class;
        $this->route        = '/menus-items';

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
        // Get Menu ID
        $menu_id = $request->menu_id;

        // Build view data
        $data = $this->commonViewData( 'Keewe | Administração', 'Plataforma Keewe' );

        $data['action']      = 'store';
        $data['form_action'] = url('/menus-items');
        $data['target_options'] = $this->getTargetOptions();
        $data['menu_id']     = $menu_id;

        if( $menu_id )
        {
            // Get Menu name
            $data['menu_name'] = Menu::find($menu_id)->value('name');

            // Load MenuItems & Convert to jsTree Component
            $items  = MenuItem::scoped([ 'menu_id' => $menu_id ])->active()->defaultOrder()->get()->toTree();
            $jstree = NestedSetFormatter::toTreeComponent( $items, 'id', 'name' );

            // Encode data
            $data['items'] = json_encode( $jstree );
        }

        //dd( $data );

        return $this->page( 'master.menuitems', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //return parent::store( $request );
        // Resolve model
        $this->resolveModel();

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Return
        $data = [
            'data' => json_decode( json_encode( $this->model ) ),
            'next_page' => url( $this->route . '?menu_id=' . $request->menu_id )
        ];

        return $this->responseSuccess( 'Operação concluída com sucesso!', $data );
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
        $data['item'] = MenuItem::find($id);
        $data['form_action'] = url('/menus-items/' . $id);

        return $this->responseSuccess('Item carregado', $data);
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
    protected function getTargetOptions()
    {
        $options = [
            (object)['value' => 'page', 'label' => 'Página'],
            (object)['value' => 'url', 'label' => 'URL interna'],
            (object)['value' => 'external', 'label' => 'URL externa'],
        ];

        return json_encode( $options );
    }

}
