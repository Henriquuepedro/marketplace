<?php
namespace App\Services\Shipping;

use App\Models\Store\Cart;
use App\Models\Store\CartItem;
use App\Models\Store\Product;
use App\Services\Shipping\Correios;

/**
 * The Shipping Service class
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 1.0
 * @copyright (c) 2020
 */
class ShippingService
{
    /**
     * Calculates the Shipping from Cart items
     *
     * @return bool
     */
    public static function calculate( $zipcode = null )
    {
        // Prepare data
        $data = self::prepare( $zipcode );

        //dd( $data );

        // Get prices
        self::getPrices( $data );

        //dd( true );

        return true;
    }

    /**
     * Prepare data for getting prices
     *
     * @return array
     */
    public static function prepare( $zipcode = null )
    {
        // Get vars
        $cep   = $zipcode ?? request()->cep;
        $cart  = get_cart();
        $index = 0;
        $last_store = null;

        $data = [];

        // Get items
        foreach( $cart->items as &$item )
        {
            // Get Product
            $product = Product::find( $item->product_id );

            if( ! $product )
            {
                //dump( $product );
                continue;
            }

            // Has free shipping
            if( $product->hasFreeShipping() )
            {
                //dump( $product->hasFreeShipping() ? 'has free shipping' : '' );
                continue;
            }

            // Can calculate Shipping
            if( ! $product->canCalculateShipping() )
            {
                //dump( ! $product->canCalculateShipping() ? 'cannot calculate shipping' : '' );
                continue;
            }

            // Get Store & Origin
            $store  = $product->shop;
            $origin = $store->address->zipcode;

            // Get dimensions
            $width  = $product->width;
            $height = $product->height;
            $length = $product->length;
            $weight = (int) $item->quantity * $product->weight;

            //dump( $origin );

            if( $index === 0 )
            {
                // This is the first item, add to array
                $data[] = (object)[
                    'item_id'    => $item->id,
                    'product_id' => $product->id,
                    'width'      => $width,
                    'height'     => $height,
                    'length'     => $length,
                    'weight'     => $weight,
                    'origin'     => $origin,
                    'destiny'    => $cep
                ];

                $index++;
                $last_store = $store->id;

                continue;
            }

            if( $store->id === $last_store )
            {
                // This is the same store, sum the weight
                $data[$index - 1]->weight += $weight;

                continue;
            }

            // Different Store
            $data[] = (object)[
                'item_id'    => $item->id,
                'product_id' => $product->id,
                'width'      => $width,
                'height'     => $height,
                'length'     => $length,
                'weight'     => $weight,
                'origin'     => $origin,
                'destiny'    => $cep
            ];

            $index++;
            $last_store = $store->id;
        }

        //dump( $data );

        return $data;
    }

    /**
     * Load prices from data
     *
     * @param array $data
     * @return void
     */
    public static function getPrices( $data )
    {
        foreach( $data as $row )
        {
            //dump( $row );

            // Trying to get the shipping price
            $result = Correios::calculate( $row->origin, $row->destiny, $row->width, $row->height, $row->length, $row->weight );

            //dump( $result );
            //dump( $result instanceof \App\Services\AppMessage );

            // Check for errors
            if( $result instanceof \App\Services\AppMessage )
            {
                //dump( 'What a hell!!!' );
                // We have an error
                continue;
            }

            // Update the item
            $item = CartItem::find( $row->item_id );

            $item->shipping = to_float( $result->Valor );
            $item->days     = (int) $result->PrazoEntrega;
            $item->save();

            //dump( $item );
        }
    }
}
