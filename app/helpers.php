<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Store\Store;
use App\Models\Store\Cart;
use App\Models\Store\CartItem;
use App\Models\Store\Product;
use App\Models\Store\ProductVariation;
use App\Models\Store\VariationOption;
use App\Models\Store\Category;

use App\Services\HtmlHelper;
use App\Services\AppMessage;

// FRONTEND =======================================================================================
if( ! function_exists( 'build_categories' ) )
{
    function build_categories( $nodes )
    {
        HtmlHelper::buildTopCategories( $nodes );
    }
}

if( ! function_exists( 'build_subcategories' ) )
{
    function build_subcategories( $parent )
    {
        HtmlHelper::buildSubCategories( $parent );
    }
}

if( ! function_exists( 'cbo_categories' ) )
{
    function cbo_categories( $nodes, $selected = null )
    {
        return HtmlHelper::categoriesSelect( $nodes, $selected );
    }
}

if( ! function_exists( 'cbo_categories_level_one' ) )
{
    function cbo_categories_level_one( $nodes, $selected = null )
    {
        return HtmlHelper::categoriesSelectLevelOne( $nodes, $selected );
    }
}

if( ! function_exists( 'chk_categories' ) )
{
    function chk_categories( $nodes, $selected = null )
    {
        HtmlHelper::categoriesCheckboxes( $nodes, $selected );
    }
}

if( ! function_exists( 'footer_menus' ) )
{
    function footer_menus( $categories )
    {
        HtmlHelper::buildFooterMenus( $categories );
    }
}

if( ! function_exists( 'now' ) )
{
    function now()
    {
        return Carbon::now();
    }
}

if( ! function_exists( 'price_range' ) )
{
    function price_range( $products )
    {
        $min_price = 0.0;
        $max_price = 0.0;
        $price = 0.0;

        foreach( $products as $product )
        {
            // Get product price
            $price = $product->price();

            // Add to min if this is the first
            if( $min_price < 1 )
                $min_price = $price;

            // Add to max if this is the first
            if( $max_price < 1 )
                $max_price = $price;

            // Check for min
            if( $price < $min_price )
                $min_price = $price;

            // Check for max
            if( $price > $max_price )
                $max_price = $price;
        }

        return (object)[
            'min_price' => $min_price,
            'max_price' => $max_price
        ];
    }
}

// FORMAT HELPERS =================================================================================
if( ! function_exists( 'dtf' ) )
{
    function dtf( $date, $format )
    {
        if( is_string($date) )
        {
            if( strpos($date, '/') !== false )
            {
                // BR date
                $parts = explode('/', $date);
                $date  = $parts[2] .'-'. $parts[1] .'-'. $parts[0];
            }

            $carbon = Carbon::parse( $date )->locale('pt_BR');
        }
        else
            $carbon = $date->locale('pt_BR');

        return $carbon->format( $format );
    }
}

if( ! function_exists( 'fmoney' ) )
{
    /**
     * Returns the number formatted as Currency
     *
     * @param float $price
     * @return string
     */
    function fmoney( $price )
    {
        return 'R$ ' . number_format( $price, 2, ',', '.' );
    }
}

if( ! function_exists( 'fcep' ) )
{
    /**
     * Formats the zipcode
     *
     * @param string $cep
     * @return string
     */
    function fcep( $cep )
    {
        return substr($cep, 0, 5) .'-'. substr($cep, 5);
    }
}

if( ! function_exists( 'address_from_order' ) )
{
    function address_from_order( $address_object )
    {
        $addr = $address_object->street .', '. $address_object->street_number .'<br>'
              . $address_object->neighborhood .' - '
              . $address_object->city .', '. $address_object->state .'<br>'
              . 'CEP ' . fcep($address_object->zipcode);

        return $addr;
    }
}

if( ! function_exists( 'to_float' ) )
{
    /**
     * Returns the string converted to Float
     *
     * @param string $n_string
     * @return float
     */
    function to_float( $n_string )
    {
        if( strpos($n_string, ',') === false )
            return floatval( $n_string );

        $n_string = str_replace('.', '', $n_string);
        $n_string = str_replace(',', '.', $n_string);

        return floatval( $n_string );
    }
}

if( ! function_exists( 'to_kilo' ) )
{
    /**
     * Returns the given grams value converted to kilo
     *
     * @param string|float $grams
     * @return float
     */
    function to_kilo( $grams )
    {
        if( is_string($grams) )
            $grams = to_float( $grams );

        return $grams / 1000;
    }
}

if( ! function_exists( 'fdec' ) )
{
    /**
     * Returns the decimal number formatted
     *
     * @param float $numb
     * @return string
     */
    function fdec( $numb )
    {
        return number_format( $numb, 2, ',', '.' );
    }
}

// COUPON =========================================================================================
if( ! function_exists( 'coupon_info' ) )
{
    /**
     * Returns info about given coupon
     *
     * @param Coupon $coupon
     * @return string
     */
    function coupon_info( $coupon )
    {
        $value = '';
        $type  = '';

        switch( substr($coupon->discount_type, 0, 5) )
        {
            case 'produ':
                $type = ' em produtos';
                break;
            case 'total':
                $type = ' no total';
                break;
            case 'shipp':
                $type = ' no frete';
                break;
        }

        switch( substr($coupon->discount_type, -6) )
        {
            case 'amount':
                $value = fmoney( $coupon->discount_value );
                break;
            case 'ercent':
                $value = fdec($coupon->discount_value) . '%';
                break;
        }

        return $value . $type;
    }
}

// CART ===========================================================================================
if( ! function_exists( 'count_cart' ) )
{
    /**
     * Returns the total amount of items in Cart
     *
     * @return int
     */
    function count_cart()
    {
        // Get Cart
        $cart  = get_cart();
        $count = 0;

        // Count items
        foreach( $cart->items as $i )
            $count += (int) $i->quantity;

        return $count;
    }
}

if( ! function_exists( 'get_cart' ) )
{
    /**
     * Returns the Shopping Cart
     *
     * @return Cart
     */
    function get_cart( $cart_id = null )
    {
        // Check for given Cart ID
        if( ! is_null($cart_id) )
        {
            $cart = Cart::find( $cart_id );
        }
        else
        {
            // Get User IP & ID, if logged
            $user_ip = request()->ip();
            $user_id = ( auth()->user() ? auth()->user()->id : null );

            $cart = Cart::firstOrCreate(
                ['user_ip' => $user_ip],
                ['user_id' => $user_id, 'address_id' => null]
            );
        }

        //dd( $cart );

        return $cart;
    }
}

if( ! function_exists( 'load_cart' ) )
{
    /**
     * Returns the loaded and calculated cart items
     *
     * @return \stdClass
     */
    function load_cart( $cart_id = null )
    {
        // Get Cart
        $cart  = get_cart( $cart_id );
        $items = [];
        $sumit = 0;
        $subtl = 0;
        $shipp = 0;
        $total = 0;

        // Get products
        foreach( $cart->getItems() as $i )
        {
            // Load Product
            $product = Product::find( $i->product_id );

            // Calculates item price
            $sumit = (int) $i->quantity * $product->price();

            // Get Store name & status
            $store        = Store::where('id', '=', $i->store_id)->first();
            $store_name   = $store->name;
            $store_status = $store->status;

            // Variations
            $variations = [];

            if( ! empty($i->variations) )
            {
                $vars = json_decode( $i->variations );

                if( ! is_null($vars) )
                {
                    foreach( $vars as $key => $val )
                    {
                        $variations[] = '<i>'. ProductVariation::where('id', '=', $key)->value('name') .'</i>: '
                                      . '<b>'. VariationOption::where('id', '=', $val)->value('name') .'</b>';
                    }
                }
            }

            // Add to array
            $items[] = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'image' => ($product->mainImage() ? $product->mainImage()->url : 'media/general/product-placeholder.png'),
                'quantity' => (int) $i->quantity,
                'stock' => (int) $product->quantity,
                'unit_price' => (float) $product->price(),
                'subtotal' => $sumit,
                'shipping' => (float) $i->shipping,
                'store_id' => $i->store_id,
                'store_name' => $store_name,
                'store_status' => $store_status,
                'variations' => $variations,
            ];

            // Calculates subtotal & shipping
            $subtl = $subtl + $sumit;
            $shipp = $shipp + (float) $i->shipping;
        }

        // Sums the Total
        $total = $shipp + $subtl;

        $data = (object) [
            'cart_id'    => $cart->id,
            'address_id' => $cart->address_id,
            'products'   => $items,
            'subtotal'   => $subtl,
            'shipping'   => $shipp,
            'total'      => $total
        ];

        //dd( $data );

        return $data;
    }
}

if( ! function_exists( 'is_home' ) )
{
    /**
     * Indicates if current request path is the Homepage.
     *
     * @return boolean
     */
    function is_home()
    {
        $path = request()->path();

        return ( ( $path === '/' ) ? true : false );
    }
}

if( ! function_exists( 'xml_to_json' ) )
{
    function xml_to_json( $xml )
    {
        $data = str_replace( array( "\n", "\r", "\t" ), '', $xml );
        $data = trim( str_replace( '"', "'", $data ) );
        $doc  = simplexml_load_string( $data );
        $json = json_encode( $doc );

        return json_decode( $json );
    }
}

if( ! function_exists( 'reset_token' ) )
{
    function reset_token( $size = 6 )
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $chars = str_split( $chars, 1 );
        $token = '';

        for( $i = 0; $i < $size; $i++ )
        {
            $j = mt_rand(0, count($chars) - 1);

            $token .= $chars[$j];
        }

        return $token;
    }
}

// GENERAL ========================================================================================
if( ! function_exists( 'slug' ) )
{
    function slug( $input )
    {
        return Str::slug( $input );
    }
}

if( ! function_exists( 'str_limit' ) )
{
    /**
     * Limit the given text by the given number of characters
     * @param string $string
     * @param int $limit
     * @return string
     */
    function str_limit( $string, $limit, $clear_tags = false )
    {
        if( $clear_tags === true )
            $string = strip_tags( $string );

        return Str::limit( $string, $limit );
    }
}

if( ! function_exists( 'doc_fix' ) )
{
    function doc_fix( $doc, $oper )
    {
        // Remove non-digit chars
        $document = preg_replace("/\D/", '', $doc);

        if( ($oper === 'clear') || ($oper === 'unformat') )
        {
            return $document;
        }

        if (strlen($document) === 11)
        {
            // CPF
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $document);
        }

        // CNPJ
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $document);
    }
}

// Master & Seller Menus
if( ! function_exists( 'active_if' ) )
{
    function active_if( $route, $default )
    {
        $css_class = $default;

        if( ! is_array($route) )
        {
            $route = [ $route ];
        }

        foreach( $route as $r )
        {
            if( request()->is( $r ) || request()->is( $r . '/*' ) )
            {
                $css_class = 'active show';
            }
        }

        return $css_class;
    }
}

// FORM DATA VALIDATOR
if( ! function_exists( 'is_valid' ) )
{
    /**
     * Validates input
     * @param array $input       Input data array.
     * @param string $rules      Rules array.
     * @param array $attributes  Array with attribute names for best error messages.
     * @return \App\Libs\AppMessage
     */
    function is_valid( $input, $rules, $attributes = [] )
    {
        // Creates the validator
        $validator = Validator::make( $input, $rules, [], $attributes );

        if( $validator->fails() )
        {
            $errors = $validator->errors();

            return new AppMessage( false, $errors->first() );
        }

        // No validation errors
        return new AppMessage( true );
    }
}

// HTML HELPER WITH OPTIONS FOR SELECT AND CHECKBOXES
if( ! function_exists( 'options' ) )
{
    /**
     * Builds and Returns options for select element
     * @param string $model_fqn   Fully Qualified Name of Model class.
     * @param string $ordering    Name of field that will be used for query ordering.
     * @param string $field_value Name of field that will be used for value attribute.
     * @param string $field_text  Name of field that will bem used for option text.
     * @return string
     */
    function options( $model_fqn, $ordering, $field_value, $field_text, $selected_value = null )
    {
        return HtmlHelper::options( $model_fqn, $ordering, $field_value, $field_text, $selected_value );
    }
}

if( ! function_exists( 'options_data' ) )
{
    /**
     * Builds and Returns options for select element
     * @param string $data   Data for build options.
     * @return string
     */
    function options_data( $data, $selected_value = null )
    {
        return HtmlHelper::optionsData( $data, $selected_value );
    }
}

if( ! function_exists( 'radio_group' ) )
{
    function radio_group( $name, $label, $options, $checked = null, $size = [3,3] )
    {
        $html  = '<div class="form-group col-md-'. $size[0] .'">';
        $html .=   '<label for="'. $name .'">'. $label .'</label>';
        $html .= '</div>';
        $html .= '<div class="form-group col-md-'. $size[1] .'">';

        foreach( $options as $opt )
        {
            $html .= '<div class="form-check form-check-inline">';
            $html .=   '<input class="form-check-input" type="radio" name="'. $name .'" id="'. $opt['id'] .'" value="'. $opt['value'] .'"';

            if( $opt['value'] === $checked )
                $html .= ' checked';

            $html .=   '>';
            $html .=   '<label class="form-check-label" for="'. $opt['id'] .'">'. $opt['text'] .'</label>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}

if( ! function_exists( 'accept_checkbox' ) )
{
    function accept_checkbox( $name, $label, $checked = null, $size = 6 )
    {
        $html  = '<div class="form-group col-md-'. $size .' '. $name .'">';
        $html .=   '<div class="form-check">';
        $html .=     '<input class="form-check-input" type="checkbox" name="'. $name .'" value="yes" id="'. $name .'" ';

        if( $checked === 'yes' )
            $html .= ' checked';

        $html .=     '>';
        $html .=     '<label class="form-check-label" for="'. $name .'">'. $label .'</label>';
        $html .=   '</div>';
        $html .= '</div>';

        return $html;
    }
}

// PERMISSIONS AND ROLES
if( ! function_exists( 'user_can' ) )
{
    /**
     * Indicates if current User can do given $abilities
     * @param string|array $abilities
     * @return boolean
     */
    function user_can( $abilities )
    {
        // Get current user
        $user = auth()->user();

        if( is_string($abilities) )
        {
            if( $user->can($abilities) )
                return true;
        }

        if( is_array($abilities) )
        {
            foreach( $abilities as $ability )
            {
                if( $user->can($ability) )
                    return true;
            }
        }

        return false;
    }
}

if( ! function_exists( 'user_is' ) )
{
    /**
     * Indicate if current User have a given Role assigned.
     * @param string $role
     * @return bool
     */
    function user_is( $role )
    {
        // Get current User
        $user = auth()->user();

        return $user->isA( $role );
    }
}

if( ! function_exists( 'query_on' ) )
{
    function query_on()
    {
        \DB::enableQueryLog();
    }
}

if( ! function_exists( 'query_log' ) )
{
    function query_log()
    {
        dd( \DB::getQueryLog() );
    }
}
