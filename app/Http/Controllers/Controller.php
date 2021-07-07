<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Validator;

use App\Models\Store\Category;
use App\Models\Common\Page;
use App\Services\AppMessage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var string Holds current Request path info. */
    public $request_path;

    /**
     * Creates a new Controller instance.
     */
    public function __construct()
    {
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns the common data for Views
     *
     * @param string $site_title
     * @param string $page_title
     * @return array
     */
    protected function commonViewData( string $site_title, string $page_title )
    {
        $this->request_path = request()->path();

        // Default view data
        $view_data = [
            // Template level vars
            'base_url' => url('/'),
            'site_title' => $site_title,
            // Page level vars
            'page_title' => $page_title,
            'meta_description' => null,
            'meta_keywords' => null,
            'categories' => $this->loadCategoriesTree(),
            'cart_items' => count_cart(),
            // Current URL & Breadcrumbs
            'uri'        => request()->url(),
            'breadcrumb' => $this->breadcrumb(),
            // Footer Menu
            'footer_menu' => $this->loadPageLinks(),
            // User
            'user' => $this->getUser(),
        ];

        return $view_data;
    }

    /**
     * Returns the Breadcrumb data for current request path
     *
     * @return array
     */
    protected function breadcrumb()
    {
        $req = request();

        // Store
        if( $req->is('entrar') )
        {
            $bc = [ (object)[ 'name' => 'Acesse sua conta', 'url' => url('/' . $this->request_path) ] ];
        }
        else if( $req->is('cadastro') || $req->is('cadastro/ok') )
        {
            $bc = [ (object)[ 'name' => 'Cadastro', 'url' => url('/' . $this->request_path) ] ];
        }
        else if( $req->is('criar-loja') )
        {
            $bc = [ (object)[ 'name' => 'Crie sua loja', 'url' => url('/' . $this->request_path) ] ];
        }
        else if( $req->is('pg/*') )
        {
            $bc = [ (object)[ 'name' => 'Página', 'url' => url('/' . $this->request_path) ] ];
        }
        // Store Adm
        else if( $req->is('minha-loja') )
        {
            $bc = [ (object)[ 'name' => 'Minha Loja', 'url' => url('/' . $this->request_path) ] ];
        }
        else if( $req->is('produtos') )
        {
            $bc = [
                (object)[ 'name' => 'Minha Loja', 'url' => url('/minha-loja') ],
                (object)[ 'name' => 'Meus Produtos', 'url' => url('/' . $this->request_path) ]
            ];
        }
        else
        {
            $bc = [];
        }

        return $bc;
    }

    /**
     * Returns Authenticated User, if any
     *
     * @return Users|null
     */
    protected function getUser()
    {
        if( auth()->user() )
            return auth()->user();

        return (object)[
            'id'       => null,
            'username' => null,
            'fullname' => null
        ];
    }

    /**
     * Returns Categories tree
     *
     * @return Collection
     */
    protected function loadCategoriesTree()
    {
        return Category::active()->get()->toTree();
    }

    /**
     * Returns page links to Footer Menu
     *
     * @return Collection
     */
    protected function loadPageLinks()
    {
        return Page::active()->select(['title', 'slug'])->get();
    }

    /**
     * Returns a view according to site theme.
     *
     * @param string $view
     * @param array $data
     * @return Response
     */
    protected function page( $view, $data = [] )
    {
        $theme = env('THEME');
        $vpath = $theme . '.' . $view;

        //dd( $vpath );

        return view( $vpath, $data );
    }

    protected function fixDecimal( $number )
    {
        $number = str_replace( '.', '', $number );
        $number = str_replace( ',', '.', $number );

        return floatval( $number );
    }

    // RESPONSES ==================================================================================
    /**
     * Generic json response with default application message.
     * @param AppMessage $app_message
     * @return response
     */
    protected function responseJson( $app_message )
    {
        return response()->json( $app_message->getArray() );
    }

    /**
     * Generic success json response.
     * @param string $message Optional message to be displayed to user.
     * @param \stdClass $data Optional data to be passed to frontend layer.
     * @return response
     */
    protected function responseSuccess( $message = null, $data = null )
    {
        $message = new AppMessage(true, $message, $data);

        return $this->responseJson( $message );
    }

    /**
     * Generic error json response.
     * @param string $error
     * @return response
     */
    protected function responseError( $error, $data = null )
    {
        $message = new AppMessage( false, $error, $data );

        return $this->responseJson( $message );
    }

    /**
     * Returns an AppMessage instance for Success.
     *
     * @param string $message
     * @return AppMessage
     */
    protected function getResponseSuccess( $message )
    {
        return new AppMessage( true, $message );
    }

    /**
     * Returns an AppMessage instance for Error.
     *
     * @param string $error
     * @return AppMessage
     */
    protected function getResponseError( $error )
    {
        return new AppMessage( false, $error );
    }
}
