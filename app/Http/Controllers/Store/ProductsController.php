<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Product;
use App\Models\Store\ProductCategory;
use App\Models\Store\Category;
use App\Models\Store\Store;

class ProductsController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Product::class;
        $this->route        = '/produtos';

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
		// Fields
        $fields = [
            'products.id', 'products.name', 'products.slug', 'products.featured', 'products.new', 'products.eco_friendly',
            'products.price', 'product_categories.category_id', 'categories.name AS category',
            'stores.name AS store_name', 'images.url'
        ];

        // Start Query
        $query = Product::join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('categories', 'product_categories.category_id', '=', 'categories.id')
                ->join('stores', 'products.store_id', '=', 'stores.id')
                ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
                ->leftJoin('images', 'product_images.image_id', '=', 'images.id')
                ->where('products.status', '=', Product::STATUS_ACTIVE)
                ->where('product_images.position', '=', 1);

        // Check for Category
        if( $request->has('category') )
        {
            $query->where('categories.id', '=', $request->category);
        }

        // Paginate data
        $list = $query->select( $fields )->paginate(12);

        return $this->responseSuccess( null, ['products' => $list] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, $slug )
    {
        // Search for product by slug
        $product = Product::where('slug', '=', $slug)->first();

        if( ! $product )
        {
            // Shows an error
            return redirect('/');
        }

        // Reference to Store
        $store = $product->shop;
        $shop_products = Product::join('stores', 'products.store_id', '=', 'stores.id')
                ->where('stores.id', '=', $store->id)
                ->where('stores.status', '=', Product::STATUS_ACTIVE)
				->where('products.id', '!=', $product->id)
                ->select( 'products.*' )
                ->distinct()
                ->get();

        // Build view data
        $data = $this->commonViewData( 'Keewe | '. $product->name, $product->name );

        $data['product']          = $product;
        $data['shop_products']    = $shop_products;
        $data['meta_description'] = $product->meta_description;
        $data['meta_keywords']    = $product->meta_keywords;

        $data['breadcrumb'] = [
            (object)[ 'name' => $product->name, 'url' => url('/' . $product->slug) ]
        ];

        //dd( $data );

        return $this->page( 'store.product', $data );
    }

    // OTHER PUBLIC METHODS =======================================================================
    /**
     * Shows the search results page
     *
     * @param Request $request
     * @param string $filters
     * @return Response
     */
    public function search( Request $request, $filters = null )
    {
        // Get search term & filters
        $search  = $request->input('q');
        $filters = json_decode( $request->input('f') );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Busca', 'resultado da busca' );

        // Search results
        $data['query']       = $search;
        $data['products']    = $this->searchProducts( $search, $filters );
        $data['filters']     = $filters;
        $data['price_range'] = price_range( $data['products'] );

        // Get categories of products
        $data['categories_found']  = $this->getCategories( $data['products'] );

        // Custom Breadcrumb
        $data['breadcrumb'] = [
            (object)[ 'name' => 'Resultado da busca', 'url' => url('/busca') ]
        ];

        //dd( $data );

        return $this->page( 'store.search-result', $data );
    }

    /**
     * Shows the New Products page
     *
     * @param Request $request
     * @param string $filters
     * @return Response
     */
    public function newProducts( Request $request, $filters = null )
    {
        // Get filters
        $filters = json_decode( $request->input('f') );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Novidades', 'novidades' );

        // Search results
        $data['products']    = $this->getNewProducts( $filters );
        $data['filters']     = $filters;
        $data['price_range'] = price_range( $data['products'] );

        // Get categories of products
        $data['categories_found']  = $this->getCategories( $data['products'] );

        // Custom Breadcrumb
        $data['breadcrumb'] = [
            (object)[ 'name' => 'Novidades', 'url' => url('/novidades') ]
        ];

        //dd( $data );

        return $this->page( 'store.new-products', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns products with given search term and filters
     *
     * @param string $term
     * @return Collection
     */
    protected function searchProducts( $term, $filters = null )
    {
        // Build query
		$query = Product::join('stores', 'products.store_id', '=', 'stores.id')
				->where('stores.status', '=', Product::STATUS_ACTIVE)
				->where(function($query) use ($term) {
					$query->where('products.name', 'like', '%'. $term .'%')
						->orWhere('products.description', 'like', '%'. $term .'%');
				});

        // Filters
        if( ! is_null($filters) )
        {
            // Price range
            if( is_array($filters->prc_r) )
                $query->whereBetween('products.price', [$filters->prc_r[0], $filters->prc_r[1]]);

            // New Stamp
            if( $filters->stp_n )
                $query->where('products.new', '=', 'yes');

            // Eco Stamp
            if( $filters->stp_e )
                $query->where('products.eco_friendly', '=', 'yes');

            // Cruelty Free Stamp
            if( $filters->stp_c )
                $query->where('products.cruelty_free', '=', 'yes');
        }

        return $query->paginate(16);
    }

    /**
     * Returns new products with given filters
     *
     * @param /stdClass $filters
     * @return Collection
     */
    protected function getNewProducts( $filters = null )
    {
		$fields = ['products.*'];
        // Build query
		$query = Product::join('stores', 'products.store_id', '=', 'stores.id')
				->where('stores.status', '=', Product::STATUS_ACTIVE)
				->where('products.new', '=', 'yes')
				->select( $fields );

        // Filters
        if( ! is_null($filters) )
        {
            // Price range
            if( is_array($filters->prc_r) )
                $query->whereBetween('products.price', [$filters->prc_r[0], $filters->prc_r[1]]);
        }
		
		//$query->dd();

        return $query->paginate(16);
    }

    /**
     * Returns category trees from given products
     *
     * @param Collection $products
     * @return Collection
     */
    protected function getCategories( $products )
    {
        // The result
        $result = [];

        foreach( $products as $product )
        {
            // Get Category ID
            $cat_id = ProductCategory::where('product_id', '=', $product->id)->max('category_id');

            // Get Ancestors
            $result[$cat_id] = Category::defaultOrder()->ancestorsAndSelf( $cat_id );
        }

        return $result;
    }
}
