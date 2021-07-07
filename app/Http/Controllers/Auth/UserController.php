<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Auth\User;
use App\Models\Store\Order;
use App\Services\MailService;

class UserController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = User::class;
        $this->route        = '/users';

        // List
        $this->list_columns = [
            'id', 'fullname', 'username', 'created_at as simple_date', 'status'
        ];
        $this->searchable   = ['fullname', 'username'];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
		$request->merge([
			'sort' => [
				'field' => 'name',
				'sort' => 'asc'
			]
		]);

        return parent::index( $request );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Check for logged User
        if( auth()->user() )
            return redirect('/criar-loja');

        // Get the target param
        $target = $request->input('t');
        $title  = ( $target === 'store' ) ? 'vamos começar a criação da sua loja?' : 'vamos começar seu cadastro?';

        // Build view data
        $data = $this->commonViewData( 'Keewe | Cadastre-se', $title );

        // Add target param
        $data['target'] = $target;

        return $this->page( 'store.register', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Validates input
        $validator = Validator::make( $request->all(), User::RULES_STORE );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            // Build the response message
            return $this->responseError( $errors->first() );
            //return $this->responseJson( new AppMessage( false, $errors->first() ) );
        }

        // Filter needed inputs
        $fields = array_keys( User::RULES_STORE );
        $data = $request->only( $fields );

        // Generate validation token
        $token = md5( microtime(true) );

        // Fix password
        $data['password']         = Hash::make( $data['password'] );
        $data['validation_token'] = $token;
        $data['status']           = User::STATUS_INACTIVE;

        // Create record
        $user = User::create( $data );

        // Check if User has been created
        if( ! $user )
        {
            return $this->responseError('Ocorreu um erro em nossos sistemas. por favor, tente novamente.');
        }

        //dd( $user );

        // Send mail
        MailService::sendRegister( $user->fullname, $user->username, $token );

        return $this->responseSuccess( null, [ 'next_page' => url('/cadastro/ok') ] );
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
        // Load record
        return parent::show( $request, $id );
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
    /**
     * Validates the registration token
     *
     * @param Request $request
     * @return Response
     */
    public function validateMail( Request $request )
    {
        // Get token
        $token = $request->input('token');

        if( ! $token )
            return redirect('/cadastro/falha')->with('error', 'O token de validação não foi informado.');

        // Check token
        $user = User::where('validation_token', '=', $token)->first();

        if( ! $user )
            return redirect('/cadastro/falha')->with('error', 'Esse link de validação não é válido.');

        // Token ok, update User
        $user->validation_token = null;
        $user->email_verified_at = now();
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        return redirect('/cadastro/validado');
    }

    /**
     * Shows the Login page
     *
     * @param Request $request
     * @return Response
     */
    public function showLoginPage( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Entrar na conta', 'acesse sua conta' );

        // Target
        if( $request->has('t') )
            $data['target'] = $request->input('t');

        // Cart
        if( $request->has('cart') )
            $data['cart'] = $request->input('cart');

        //dd( $data );

        return $this->page( 'store.login', $data );
    }

    /**
     * Shows the Register page
     *
     * @param Request $request
     * @return Response
     */
    public function showRegisterPage( Request $request )
    {

    }

    /**
     * Shows the User Account page
     *
     * @param Request $request
     * @return Response
     */
    public function myAccount( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Minha conta', 'minha conta' );

        $user = auth()->user();

        $data['form_action'] = url('/users/' . $user->id);
        $data['action'] = 'update';
        $data['me'] = $user;

        return $this->page( 'user.account', $data );
    }

    /**
     * Shows the Change Password page
     *
     * @param Request $request
     * @return Response
     */
    public function myPassword( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Minha conta', 'alterar senha' );

        $user = auth()->user();

        $data['form_action'] = url('/mudar-senha');
        $data['me'] = $user;

        return $this->page( 'user.password', $data );
    }

    /**
     * Updates User's Password
     *
     * @param Request $request
     * @return Response
     */
    public function updatePassword( Request $request )
    {
        // First, get User & current password
        $user  = auth()->user();
        $cpass = $request->cur_pass;

        // Verify current password
        if( ! Hash::check( $cpass, $user->password ) )
        {
            return $this->responseError( 'Sua senha atual não está correta.' );
        }

        // Rules for new password
        $rules = [ 'password' => 'bail|required|confirmed|string|min:6', ];

        // Validates input
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            // Build the response message
            return $this->responseError( $errors->first() );
            //return $this->responseJson( new AppMessage( false, $errors->first() ) );
        }

        // All passed, updates password
        $user->password = Hash::make( $request->password );
        $user->save();

        return $this->responseSuccess( 'Sua senha foi alterada com sucesso.' );
    }

    /**
     * Shows the User orders page
     *
     * @param Request $request
     * @return Response
     */
    public function myOrders( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Minha conta', 'Meus pedidos' );

        $user = auth()->user();

        //$data['form_action'] = url('/mudar-senha');
        $data['me'] = $user;
        $data['orders'] = $this->getMyOrders( $user->id );

        //dd($data);

        return $this->page( 'user.orders', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Return my Orders
     *
     * @param integer $uid
     * @return array|Collection
     */
    protected function getMyOrders( $uid )
    {
        return Order::where('user_id', '=', $uid)->orderBy('updated_at', 'desc')->paginate(10);
    }
}
