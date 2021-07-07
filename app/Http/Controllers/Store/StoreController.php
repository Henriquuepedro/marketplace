<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Auth\User;
use App\Models\Location\Address;
use App\Models\Store\Store;
use App\Models\Store\Product;

class StoreController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Store::class;
        $this->route        = '/stores';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
		// Build view data
        $data = $this->commonViewData( 'Keewe | Lojas', 'Índice de Lojas' );

        $data['alphabet'] = str_split( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' );
        $data['stores']   = Store::active()->orderBy('name')->get();

        return $this->page( 'store.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // The title
        $title  = 'vamos começar a criação da sua loja?';

        // Build view data
        $data = $this->commonViewData( 'Keewe | Criar Loja', $title );

        // Get User
        $user = auth()->user();

        if( $user )
        {
            $data['user'] = $user;
            $data['user_id'] = $user->id;
        }
        else
        {
            $uid = $request->input('u');;

            $data['user'] = User::find($uid);
            $data['user_id'] = $uid;
        }

        return $this->page( 'store.create-store', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // First, make and check the Store slug
        $slug = slug( $request->name );

        $exists = Store::where('slug', '=', $slug)->first();

        if( $exists )
        {
            return $this->responseError( 'O nome da loja já está sendo utilizado. Por favor, escolha um outro nome.' );
        }

        // Merge slug
        $request->merge(['slug' => $slug]);

        // Change model to Address temporarily
        $this->model_name = Address::class;

        // Validates and Saves Address
        $this->validateAndSave( $request->all(), 'store' );

        // Get Address ID
        $address_id = $this->model->id;

        $request->merge(['address_id' => $address_id]);

        // Reset Model
        $this->resetModel( Store::class );

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Data to be returned
        $data = [ 'next_page' => url('/') ];

        return $this->responseSuccess( 'Sua loja foi criada!', $data );
    }

    /**
     * Shows the Store Page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function showPage( Request $request, $slug )
    {
        // Load Store
        $store = Store::where('slug', '=', $slug)->first();

        if( ! $store )
        {
            return redirect('/lojas');
        }

        // Build view data
        $data = $this->commonViewData( 'Keewe | Lojas', $store->name );

        $data['store']    = $store;
        $data['products'] = Product::where('store_id', '=', $store->id)->get();

        return $this->page( 'store.show', $data );
    }
}
