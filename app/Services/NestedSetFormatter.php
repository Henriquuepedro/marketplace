<?php

namespace App\Services;

class NestedSetFormatter
{
    /**
     * Returns the Collection to pass to jsTree component.
     * @param Collection $collection
     * @param string $id_field
     * @param string $text_field
     * @return string
     */
    public static function toTreeComponent( $collection, $id_field, $text_field )
    {
        $tree = self::jsTreeJson( $collection, $id_field, $text_field );

		return $tree;
        //return json_encode( $tree );
    }

    /**
     * Converts jsTree component array to NestedSet array for database update
     * @param array $items
     * @param string $id_field
     * @param string $text_field
     * @return array
     */
    public static function toDatabase( $items, $id_field, $text_field )
    {
        $temp = [];

        foreach( $items as $item )
        {
            $it = [ $id_field => $item['id'], $text_field => $item['text'] ];

            if( isset( $item['children'] ) )
            {
                $it['children'] = self::toDatabase( $item['children'], $id_field, $text_field );
            }

            $temp[] = $it;
        }

        return $temp;
    }

    /**
     * Traverse the Collection to build Json for jsTree component.
     * @param Collection $collection
     * @param string $id_field
     * @param string $text_field
     * @return array
     */
    protected static function jsTreeJson( $collection, $id_field, $text_field )
    {
        $temp = [];

        foreach( $collection as $item )
        {
            $temp[] = (object)[
                'id'   => $item->{$id_field},
                'text' => $item->{$text_field},
                'state' => (object)['opened' => true],
                'children' => self::jsTreeJson( $item->children, $id_field, $text_field )
            ];
        }

        return $temp;
    }
}