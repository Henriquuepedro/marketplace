<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Auth\Wishlist;
use App\Models\Store\Product;

class WishlistController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Wishlist::class;
        $this->route        = '/wishlist';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    /**
     * Display the User's Wishlist
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Get User
        $user = auth()->user();

        if( ! $user )
            return redirect('/entrar?t=wishlist');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Wishlist', 'Minha Wishlist' );

        // Load Wishlist
        $wishlist = Product::join('wishlists', 'products.id', '=', 'wishlists.product_id')
                ->where('wishlists.user_id', '=', $user->id)
                ->select('products.*')
                ->get();

        $data['wishlist'] = $wishlist;

        return $this->page( 'user.wishlist', $data );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
    {
        // Get User
        $user = auth()->user();

        if( ! $user )
            return redirect('/entrar?t=wishlist');

        $deleted = Wishlist::where('product_id', '=', $id)
                ->where('user_id', '=', $user->id)
                ->delete();

        if( $deleted )
            return $this->responseError( 'Ocorreu uma falha ao remover o item. Por favor, tente novamente.' );

        return $this->responseSuccess( 'O item foi removido da sua Wishlist.' );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================

}
