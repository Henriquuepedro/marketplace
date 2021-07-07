<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Store\Store;
use App\Models\Store\Product;
use App\Models\Store\ProductCategory;
use App\Models\Store\ProductImage;

use App\Services\Shipping\Correios;

use App\Services\AppMessage;

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
     * In this case (admin), display the Products list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Store data & Products
        $data['store'] = $store;
        $data['products'] = Product::where('store_id', '=', $store->id)->get();

        return $this->page( 'seller.products-list', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Check for Bank Info
        if( is_null($store->info) )
            return redirect('/minha-loja/bank');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get dashboard data
        $data['form_action'] = url('/produtos');
        $data['store']       = $store;
        $data['action']      = 'create';
        $data['images']      = [];

        return $this->page( 'seller.products-form', $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Make and check Product Slug
        $slug = slug( $request->name );

        $exists = Product::where('slug', '=', $slug)->first();

        if( $exists )
        {
            return $this->responseError( 'O nome do produto já está sendo utilizado. Por favor, escolha outro nome.' );
        }

        // Merge slug
        $request->merge(['slug' => $slug]);

        // Categories and Images
        // Categories and Images
        $this->validateCategories( $request );
        $this->validateImages( $request );
        $this->validateStamps( $request );

        // Fix numbers
        $this->fixNumbers( $request );

        // Make the slug
        $request->merge(['slug' => slug($request->name)]);

        // Save product data
        $response = parent::store( $request );

        // Alias to Product ID
        $product_id = $this->model->id;

        // Link categories
        $this->linkCategories( $product_id, $request->categories );

        // Link images
        $this->linkImages( $product_id, $request->images );

        // Data to be returned
        $data = [ 'next_page' => url('/produtos') ];

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
        //
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
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Resolve model
        $this->resolveModel( $id );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Get product categories
        $cats = $this->model->categories;
        $pc   = [];

        foreach( $cats as $prod_cat )
        {
            $pc[] = $prod_cat->id;
        }

        // Get dashboard data
        $data['form_action'] = url('/produtos/' . $id);
        $data['store']       = $store;
        $data['action']      = 'update';
        $data['product']     = $this->model;
        $data['images']      = $this->model->images;
        $data['prod_cat']    = $pc;

        return $this->page( 'seller.products-form', $data );
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
        // Re-make and check Product Slug
        $slug = slug( $request->name );

        $exists = Product::where('slug', '=', $slug)->where('id', '!=', $id)->first();

        if( $exists )
        {
            return $this->responseError( 'O nome do produto já está sendo utilizado. Por favor, escolha outro nome.' );
        }

        // Merge slug
        $request->merge(['slug' => $slug]);

        // Validates Categories, Images & Stamps
        $this->validateCategories( $request );
        $this->validateImages( $request );
        $this->validateStamps( $request );

        // Fix numbers
        $this->fixNumbers( $request );

        // Make the slug
        $request->merge(['slug' => slug($request->name)]);

        // Save product data
        $response = parent::update( $request, $id );

        // Alias to Product ID
        $product_id = $this->model->id;

        // Link categories
        $this->linkCategories( $product_id, $request->categories, true );

        // Link images
        $this->linkImages( $product_id, $request->images, true );

        // Data to be returned
        $data = [ 'next_page' => url('/produtos') ];

        return $this->responseSuccess( null, $data );
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
     * Shows the Sold Out page
     *
     * @param Request $request
     * @return Response
     */
    public function soldOut( Request $request )
    {
        // Check Store
        $store = $this->checkStore();

        if( ! $store )
            return \redirect('/criar-loja');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Minha Loja', $store->name );

        // Store data & Products
        $data['store'] = $store;
        $data['products'] = Product::where('store_id', '=', $store->id)->where('quantity', '<=', 0)->get();

        return $this->page( 'seller.soldout-list', $data );
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Validates Categories
     *
     * @param Request $request
     * @return void
     */
    protected function validateCategories( Request $request )
    {
        $categories = $request->categories;
        $result = true;

        if( ! $categories )
        {
            $result = false;
        }
        else if( ! is_array($categories) )
        {
            $result = false;
        }
        else if( count($categories) < 3 )
        {
            $result = false;
        }
        else
        {
            foreach( $categories as $cat )
            {
                if( empty($cat) )
                {
                    $result = false;
                    break;
                }
            }
        }

        if( $result === false )
        {
            abort( $this->responseJson( new AppMessage( false, 'Por favor, selecione as Categorias desse produto.' ) ) );
        }
    }

    /**
     * Validates Images
     *
     * @param Request $request
     * @return void
     */
    protected function validateImages( Request $request )
    {
        $images = $request->images;
        $result = true;

        if( ! $images )
        {
            $result = false;
        }
        else if( ! is_array($images) )
        {
            $result = false;
        }
        else
        {
            foreach( $images as $img )
            {
                if( empty($img) )
                {
                    $result = false;
                    break;
                }
            }
        }

        if( $result === false )
        {
            abort( $this->responseJson( new AppMessage( false, 'Por favor, envie ao menos uma (1) imagem do produto.' ) ) );
        }
    }

    /**
     * Validates the Stamps
     *
     * @param Request $request
     * @return void
     */
    protected function validateStamps( Request $request )
    {
        // Get stamps & Accepts
        $eco_stamp  = $request->eco_friendly;
        $eco_accept = $request->eco_friendly_accepted;

        $veg_stamp  = $request->cruelty_free;
        $veg_accept = $request->cruelty_free_accepted;

        // Eco
        if( ($eco_stamp == 'yes') && ($eco_accept != 'yes') )
        {
            abort( $this->responseJson( new AppMessage( false, 'Para utilizar o selo Eco-Friendly você deve aceitar os termos.' ) ) );
        }

        // Veg
        if( ($veg_stamp == 'yes') && ($veg_accept != 'yes') )
        {
            abort( $this->responseJson( new AppMessage( false, 'Para utilizar o selo Vegano & Cruelty Free você deve aceitar os termos.' ) ) );
        }
    }

    /**
     * Link products and categories
     *
     * @param int $product_id
     * @param array $categories
     * @return void
     */
    protected function linkCategories( $product_id, $categories, $clear = false )
    {
        if( $clear )
        {
            // Delete previous linked records
            ProductCategory::where('product_id', '=', $product_id)->delete();
        }

        if( ! is_array( $categories ) )
            $categories = [ $categories ];

        foreach( $categories as $category_id )
        {
            if( empty($category_id) )
                continue;

            ProductCategory::create([
                'product_id' => $product_id,
                'category_id' => $category_id
            ]);
        }
    }

    /**
     * Link products and categories
     *
     * @param int $product_id
     * @param array $images
     * @return void
     */
    protected function linkImages( $product_id, $images, $clear = false )
    {
        if( $clear )
        {
            // Delete previous linked records
            ProductImage::where('product_id', '=', $product_id)->delete();
        }

        if( ! is_array( $images ) )
            $images = [ $images ];

        $position = 1;

        foreach( $images as $image_id )
        {
            if( empty($image_id) )
                continue;

            ProductImage::create([
                'product_id' => $this->model->id,
                'image_id' => $image_id,
                'position' => $position
            ]);

            $position++;
        }
    }

    /**
     * Fix numbers
     *
     * @param Request $request
     * @return void
     */
    protected function fixNumbers( Request $request )
    {
        $input = [
            'width'      => $request->width,
            'height'     => $request->height,
            'length'     => $request->length,
            'weight'     => $request->weight,
            'price'      => $request->price,
            'old_price'  => $request->old_price,
            'tax'        => $request->tax
        ];

        foreach( $input as &$i )
        {
            $i = to_float( $i );
        }

        // Validate dimensions
        $this->validateLimits( $input );

        $request->merge( $input );
    }

    /**
     * Validates dimensions and Weight
     *
     * @param array $input
     * @return AppMessage|bool
     */
    protected function validateLimits( $input )
    {
        $res = Correios::validateLimits( $input['width'], $input['height'], $input['length'], to_kilo($input['weight']) );

        if( $res !== true )
        {
            abort( $this->responseJson( $res ) );
        }

        return true;
    }
}
