<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Auth\User;
use App\Services\MailService;

class AuthController extends Controller
{
    /**
     * Handler for User Login
     *
     * @param Request $request
     * @return Response
     */
    public function handleLogin( Request $request )
    {
        // The rules
        $rules = [
            'username' => 'bail|required|email|exists:users,username',
            'password' => 'bail|required|string|min:6',
            //'lang'     => 'nullable|string|in:en,es,pt-br'
        ];

        // Validates input
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            // Build the response message
            return $this->responseError( $errors->first() );
        }

        // Validation ok, build credentials
        $credentials = $request->only(['username', 'password']);

        // Add status
        $credentials['status'] = User::STATUS_ACTIVE;

        // Remember Me
        $remember = ($request->remenber === 'yes') ? true : false;

        // Attempt to login
        if( ! Auth::attempt( $credentials, $remember ) )
        {
            return $this->responseError( 'As credenciais digitadas não são válidas. Por favor, verifique-as.' );
        }

        // Login successfully
        // Get User
        $user = Auth::user();

        // Data to be returned
        $msg  = 'Seja muito bem vindo, ' . $user->fullname . '!';

        // Next page
        $data = [
            'next_page' => url('/' . $request->input('target'))
        ];

        // If login redirect to Checkout, we need a Cart ID
        if( $request->has('cart') )
        {
            $cart = $request->input('cart');

            if( ! is_null($cart) )
                $data['next_page'] .= '?cart=' . $request->cart;
        }

        return $this->responseSuccess( $msg, $data );
    }

    /**
     * Handler for User logout
     *
     * @param Request $request
     * @return Response
     */
    public function handleLogout( Request $request )
    {
        $fullname = Auth::user()->fullname;

        // Logout
        Auth::logout();

        // Session clear
        $request->session()->invalidate();

        if( $request->isMethod('post') )
        {
            // Data to be returned
            $msg  = 'Até logo, ' . $fullname . '! Esperamos vê-lo novamente por aqui.';
            $data = [
                'next_page' => url('/')
            ];

            return $this->responseSuccess( $msg, $data );
        }

        // GET method, make a redirect
        return redirect('/');
    }

    /**
     * Shows the Forgot Password page
     *
     * @param Request $request
     * @return Response
     */
    public function showForgotPage( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Esqueci a senha', 'esqueci a senha' );

        //dd( $data );

        return $this->page( 'store.forgot', $data );
    }

    /**
     * Sends the Password Reset token by email
     *
     * @param Request $request
     * @return Response
     */
    public function handleForgotPass( Request $request )
    {
        // The rules
        $rules = [ 'username' => 'bail|required|email|exists:users,username' ];

        // Validates input
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            // Build the response message
            return $this->responseError( $errors->first() );
        }

        // Generate the Token
        $token = reset_token( 6 );

        // Saves to database
        \DB::table('password_resets')->insert([
            'email' => $request->username,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()
        ]);

        // Send mail
        MailService::sendPasswordReset( $request->username, $token );

        return $this->responseSuccess( null, [ 'next_page' => url('/redefinir-senha?email=' . $request->username) ] );
    }

    /**
     * Shows the Reset Password form
     *
     * @param Request $request
     * @return Response
     */
    public function showResetPage( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Redefinição de senha', 'redefinir minha senha' );

        $data['email'] = $request->email;

        //dd( $data );

        return $this->page( 'store.reset', $data );
    }

    public function resetPassword( Request $request )
    {
        // The rules
        $rules = [
            'username' => 'bail|required|email|exists:users,username',
            'token'    => 'bail|required|string|size:6',
            'password' => 'bail|required|string|min:6'
        ];

        // Validates input
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            // Build the response message
            return $this->responseError( $errors->first() );
        }

        // Check token
        $pr = \DB::table('password_resets')
            ->where('email', $request->username)
            ->where('token', $request->token)
            ->first();

        if( ! $pr )
        {
            return $this->responseError( 'O código digitado não é válido' );
        }

        // Get User
        $user = User::where('username', $request->username)->first();

        if( ! $user )
        {
            return $this->responseError( 'O usuário não foi encontrado.' );
        }

        // Reset pass
        $user->password = Hash::make( $request->password );
        $user->save();

        // Delete token
        \DB::table('password_resets')->where('email', $request->username)->where('token', $request->token)->delete();

        return $this->responseSuccess( 'Sua senha foi redefinida com sucesso', [ 'next_page' => url('/entrar')] );
    }
}
