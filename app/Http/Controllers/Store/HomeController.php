<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Auth\User;
use App\Models\Store\Product;
use App\Models\Store\Category;

class HomeController extends Controller
{
    /**
     * Shows the Store Homepage
     *
     * @param Request $request
     * @return Response
     */
    public function home( Request $request )
    {
        $data = $this->commonViewData( 'Keewe', 'keewe' );

        // Carousel
        $data['carousel'] = [
            (object) [ 'image' => '/media/carousel/casa.png', 'url' => url('/categoria/casa') ],
            (object) [ 'image' => '/media/carousel/genderless.png', 'url' => url('/categoria/genderless') ],
            (object) [ 'image' => '/media/carousel/homem-2.png', 'url' => url('/categoria/homens') ],
            (object) [ 'image' => '/media/carousel/kids.png', 'url' => url('/categoria/kids') ],
            (object) [ 'image' => '/media/carousel/lifestyle-certo.png', 'url' => url('/categoria/lifestyle') ],
            (object) [ 'image' => '/media/carousel/mulher-2.png', 'url' => url('/categoria/mulheres') ],
            (object) [ 'image' => '/media/carousel/office.png', 'url' => url('/categoria/office') ],
            (object) [ 'image' => '/media/carousel/onthemove.png', 'url' => url('/categoria/on-the-move') ],
            (object) [ 'image' => '/media/carousel/pet.png', 'url' => url('/categoria/pets') ]
        ];

        // Select New Products
        $data['new_products'] = $this->getNewProducts();

        // Gifts for her & Gifts for kids
        $data['gifts_for_her']  = $this->getFeaturedForWoman();
        $data['gifts_for_kids'] = $this->getFeaturedForKids();

        return $this->page( 'store.home', $data );
    }


    /**
     * Shows the Register feedback page
     *
     * @param Request $request
     * @return Response
     */
    public function registerFeedback( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Cadastre-se', 'Parabéns!' );

        return $this->page( 'store.register_ok', $data );
    }

    /**
     * Shows the Validated feedback page
     *
     * @param Request $request
     * @return Response
     */
    public function validationFeedback( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Cadastre-se', 'Parabéns!' );

        return $this->page( 'store.register_validated', $data );
    }

    /**
     * Shows the Error feedback page
     *
     * @param Request $request
     * @return Response
     */
    public function errorFeedback( Request $request )
    {
        $data = $this->commonViewData( 'Keewe | Falha', 'Algo não deu certo' );

        $data['error'] = session('error');

        return $this->page( 'store.error_page', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns the list of New Products
     *
     * @return Collection
     */
    protected function getNewProducts()
    {
        $list = Product::join('stores', 'products.store_id', '=', 'stores.id')
                ->where('products.new', '=', 'yes')
                ->where('stores.status', '=', Product::STATUS_ACTIVE)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->select('products.*')
                ->get();

        return $list;
    }

    /**
     * Returns the list of New Products
     *
     * @return Collection
     */
    protected function getFeaturedForWoman()
    {
        // Get Woman category
        $woman = Category::where('slug', '=', 'mulheres')->first();
        $tree  = Category::defaultOrder()->descendantsAndSelf( $woman->id )->pluck('id')->toArray();

        //dd( $tree );

        $query = Product::join('stores', 'products.store_id', '=', 'stores.id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('stores.status', '=', Product::STATUS_ACTIVE)
                ->whereIn('product_categories.category_id', $tree)
                ->where('products.featured', '=', 'yes')
                ->where('products.status', '=', 'active')
                ->inRandomOrder()
                ->select('products.*')
                ->distinct()
                ->limit(8);
        
        //$query->dump();
        $list = $query->get();

        return $list;
    }

    /**
     * Returns the list of New Products
     *
     * @return Collection
     */
    protected function getFeaturedForKids()
    {
        // Get Kids category
        $woman = Category::where('slug', '=', 'kids')->first();
        $tree  = Category::defaultOrder()->descendantsAndSelf( $woman->id )->pluck('id')->toArray();

        //dd( $tree );

        $query = Product::join('stores', 'products.store_id', '=', 'stores.id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('stores.status', '=', Product::STATUS_ACTIVE)
                ->whereIn('product_categories.category_id', $tree)
                ->where('products.featured', '=', 'yes')
                ->where('products.status', '=', 'active')
                ->inRandomOrder()
                ->select('products.*')
                ->distinct()
                ->limit(8);
    
        //$query->dump();
        $list = $query->get();

        return $list;
    }
}
