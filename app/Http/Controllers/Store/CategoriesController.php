<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Category;
use App\Models\Store\Product;

class CategoriesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Category::class;
        $this->route        = '/categories';

        // List
        $this->list_columns = [
            'id', 'name', 'description', 'created_at as simple_date', 'status'
        ];
        $this->searchable   = ['name'];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $parent = null )
    {
		if( is_null( $parent ) )
        {
            // Level one
            $categories = Category::active()->defaultOrder()->whereNull('parent_id')->get();
        }
        else
        {
            // Load parent
            $node = Category::find( $parent );

            $categories = $node->children;
        }

        $data = [
            'categories' => $categories
        ];

        return $this->responseSuccess( null, $data );
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

    // OTHER PUBLIC METHODS =======================================================================
    /**
     * Shows the Category Home
     *
     * @param Request $request
     * @param string $level0
     * @return Response
     */
    public function categoryHome( Request $request, $level0 )
    {
        // Get main category
        $main = Category::where('slug', '=', $level0)->first();

        // Build view data
        $data = $this->commonViewData( 'Keewe | ' . $main->name, $main->name );

        $data['main_category'] = $main;

        // Get products of this Category
        $data['products'] = $this->getCategoryProducts( $main->id );

        // Custom Breadcrumb
        $data['breadcrumb'] = [
            (object)[ 'name' => $main->name, 'url' => url('/categoria/' . $main->slug) ]
        ];

        //dd( $data );

        return $this->page( 'store.category-home', $data );
    }

    /**
     * Shows the Subcategory Page
     *
     * @param Request $request
     * @param string $level0
     * @param string $level1
     * @param string $level2
     * @return void
     */
    public function subcategoryPage( Request $request, $level0, $level1 = null, $level2 = null )
    {
        // Get Categories
        $main = $this->getBySlug( $level0 );
        $lvl1 = $this->getBySlug( $level0 .'/'. $level1 );
        $lvl2 = $this->getBySlug( $level0 .'/'. $level1 .'/'. $level2 );

        // Title, ID and Filters
        $title   = $main->name .' - '. $lvl1->name . ( $lvl2 ? ' - '. $lvl2->name : '' );
        $cat_id  = ( $lvl2 ? $lvl2->id : $lvl1->id );
        $filters = json_decode( $request->input('f') );

        //dd( $filters );

        // Build view data
        $data = $this->commonViewData( 'Keewe | ' . $title, $main->name );

        $data['main_category'] = $main;
        $data['category_lvl1'] = $lvl1;
        $data['category_lvl2'] = $lvl2;
        $data['filters']       = $filters;

        // Get products of this Category
        $data['products'] = $this->getCategoryProducts( $cat_id, $filters );
        $data['price_range'] = price_range( $data['products'] );

        // Custom Breadcrumb
        $data['breadcrumb'] = $this->subcategoryBreadcrumbs( $main, $lvl1, $lvl2 );

        //dd( $data );

        return $this->page( 'store.category-sub', $data );
    }

    /**
     * Shows the Category Page
     *
     * @param Request $request
     * @param string $level0
     * @param string $level1
     * @param string $level2
     * @return void
     */
    public function categoryPage( Request $request, $level0, $level1 = null, $level2 = null )
    {
        // Get main category
        $main = Category::where('slug', '=', $level0)->first();
        $lvl1 = null;
        $lvl2 = null;

        // Level 1
        if( ! is_null( $level1 ) )
        {
            $slug = $level0 .'/'. $level1;
            $lvl1 = Category::where('slug', '=', $slug)->first();
        }

        // Level 2
        if( ! is_null( $level2 ) )
        {
            $slug = $level0 .'/'. $level1 .'/'. $level2;
            $lvl2 = Category::where('slug', '=', $slug)->first();
        }

        // Current category
        $curr = ( $lvl2 ?? ( $lvl1 ?? $main ) );

        // Build view data
        $data = $this->commonViewData( 'Keewe | ' . $curr->name, $curr->name );

        $data['main_category'] = $main;
        $data['category'] = $curr;

        // Get products of this Category
        $data['products'] = $this->getCategoryProducts( $curr->id );

        // Custom Breadcrumb
        $data['breadcrumb'] = [
            (object)[ 'name' => $main->name, 'url' => url('/categoria/' . $main->slug) ]
        ];

        if( $lvl1 )
        {
            $data['breadcrumb'][] = (object)[ 'name' => $lvl1->name, 'url' => url('/categoria/' . $lvl1->slug) ];
        }

        if( $lvl2 )
        {
            $data['breadcrumb'][] = (object)[ 'name' => $lvl2->name, 'url' => url('/categoria/' . $lvl2->slug) ];
        }

        //dd( $data );

        return $this->page( 'store.category', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Returns products from given category
     *
     * @param int $cid
     * @return Collection
     */
    protected function getCategoryProducts( $cid, $filters = null )
    {
        // Fields
        $fields = [
            'products.*',
            /*
            'products.id', 'products.name', 'products.slug', 'products.featured', 'products.new', 'products.eco_friendly',
            'products.price', 'product_categories.category_id', 'categories.name AS category',
            'stores.name AS store_name', 'images.url'
            */
        ];

        // Start Query
        $query = Product::join('stores', 'products.store_id', '=', 'stores.id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('stores.status', '=', Product::STATUS_ACTIVE)
                ->where('products.status', '=', Product::STATUS_ACTIVE)
                ->where('product_categories.category_id', '=', $cid);

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

        // End query
        $query->select( $fields )->distinct();

        return $query->paginate(12);
    }

    protected function getBySlug( $slug )
    {
        return Category::where('slug', '=', $slug)->first();
    }

    /**
     * Returns the Breadcrumbs for SubCategory page
     *
     * @param Category $main
     * @param Category $lvl1
     * @param Category $lvl2
     * @return array
     */
    protected function subcategoryBreadcrumbs( $main, $lvl1, $lvl2 )
    {
        $breadcrumb = [
            (object)[ 'name' => $main->name, 'url' => url('/categoria/' . $main->slug) ]
        ];

        if( $lvl1 )
        {
            $breadcrumb[] = (object)[ 'name' => $lvl1->name, 'url' => url('/categoria/' . $lvl1->slug) ];
        }

        if( $lvl2 )
        {
            $breadcrumb[] = (object)[ 'name' => $lvl2->name, 'url' => url('/categoria/' . $lvl2->slug) ];
        }

        return $breadcrumb;
    }
}
