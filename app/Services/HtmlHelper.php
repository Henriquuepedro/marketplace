<?php
namespace App\Services;

use App\Models\Common\Page;
use App\Models\Common\Menu;
use App\Models\Common\MenuItem;
use App\Services\AppMessage;

/**
 * Helper class to work with Html
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2019, GoUp Digital
 */
class HtmlHelper
{
    // OPTIONS FOR SELECT ELEMENTS ================================================================
    /**
     * Builds and Returns options for select element
     * @param string $model_fqn   Fully Qualified Name of Model class.
     * @param string $ordering    Name of field that will be used for query ordering.
     * @param string $field_value Name of field that will be used for value attribute.
     * @param string $field_text  Name of field that will bem used for option text.
     * @return string
     */
    public static function options( $model_fqn, $ordering, $field_value, $field_text, $selected = null )
    {
        $options = [];
        $model   = resolve( $model_fqn );
        $items   = $model->active()->orderBy( $ordering )->select( $field_value, $field_text )->get();

        foreach( $items as $item )
        {
            $opt = '<option value="'. $item->{$field_value} .'"';

            // Check if selected value is array
            if( is_array($selected) )
            {
                if( in_array($item->{$field_value}, $selected) )
                    $opt .= ' selected';
            }
            else
            {
                if( $item->{$field_value} === $selected )
                    $opt .= ' selected';
            }

            // Close option tag
            $opt .= '>'. $item->{$field_text} .'</option>';

            // Add to options array
            $options[] = $opt;
        }

        return implode( '', $options );
    }

    /**
     * Builds and Returns options for select element
     * @param string $data   Data for build options.
     * @param int $selected  Value that needs to be selected.
     * @return string
     */
    public static function optionsData( $data, $selected = null )
    {
        $options = [];
        $json    = json_decode( $data );

        if( is_null($json) )
            return $data;

        foreach( $json as $item )
        {
            $temp = '<option value="'. $item->value .'"';

            if( is_array($selected) )
            {
                foreach( $selected as $sel )
                {
                    if( $item->value === $sel )
                        $temp .= ' selected';
                }
            }
            else
            {
                if( $item->value === $selected )
                    $temp .= ' selected';
            }

            $temp .= '>'. $item->label .'</option>';

            // Add to main array
            $options[] = $temp;
        }

        return implode( '', $options );
    }

    // CATEGORIES =================================================================================
    /**
     * Builds the Categories menu on Header
     *
     * @param Collection $nodes
     * @return void
     */
    public static function buildTopCategories( $nodes )
    {
        //$nodes  = Category::get()->toTree();
        $first  = true;

        // Recursive function
        $traverse = function( $categories, $level = 0 ) use (&$traverse, &$first)
        {
            foreach( $categories as $category )
            {
                // Build URL
                $url = url('/categoria/' . $category->slug);

                if( $level === 0 )
                {
                    if( ! $first )
                    {
                        echo '</ul>';
                        echo '</div>';
                    }

                    // Build tags
                    echo '<div class="col">';
                    echo '<ul class="list-unstyled">';
                    echo '<li><a href="'. $url .'" class="main-category">'. $category->name .'</a></li>';

                    $first = false;
                }
                else if( $level === 1 )
                {
                    // Build tags
                    echo '<li><a href="'. $url .'" class="">'. $category->name .'</a></li>';
                }
                else
                    return;

                $traverse( $category->children, $level + 1 );
            }
        };

        $traverse( $nodes );
    }

    /**
     * Builds the Sub-Categories menu
     *
     * @param Collection $parent
     * @return void
     */
    public static function buildSubCategories( $parent )
    {
        $nodes = $parent->children;
        $first = true;
        $head  = true;
        $divs  = 1;

        //
        echo '<div class="accordion" id="sub-categories">';

        // Recursive function
        $traverse = function( $categories, $level = 1 ) use (&$traverse, &$first, &$head, &$divs)
        {
            foreach( $categories as $category )
            {
                // Build URL
                $url = url('/categoria/' . $category->slug);

                if( $level === 1 )
                {
                    if( $first === false )
                    {
                        echo     '</div>';
                        echo   '</div>';
                        echo '</div>';
                    }

                    echo '<div class="card">';
                    echo   '<div class="card-header" id="heading-'. $divs .'">';
                    echo     '<a class="item-parent" href="'. $url .'" data-target="#subnav-'. $divs .'" aria-controls="subnav-'. $divs .'">';
                    echo       $category->name;
                    echo     '</a>';
                    echo   '</div>';

                    $head = true;
                }
                else if( $level === 2 )
                {
                    if( $head )
                    {
                        echo '<div id="subnav-'. $divs .'" class="collapse" aria-labelledby="heading-'. $divs .'" data-parent="#sub-categories">';
                        echo   '<div class="card-body">';

                        $first = false;
                        $head  = false;
                        $divs++;
                    }

                    echo '<a class="item-child" href="'. $url .'">' . $category->name . '</a>';
                }

                // Build tags
                //echo '<li><a href="'. $url .'" class="'. $class .'">'. $category->name .'</a></li>';

                $traverse( $category->children, $level + 1 );
            }
        };

        $traverse( $nodes );

        // Close card body, collapse and card
        echo     '</div>';
        echo   '</div>';
        echo '</div>';

        // Close accordion
        echo '</div>';
    }

    /**
     * Returns the options for Categories select element
     *
     * @param Collection $nodes
     * @param null|int $selected
     * @return string
     */
    public static function categoriesSelect( $nodes, $selected = null )
    {
        $options = [];

        // Recursive function
        $traverse = function( $categories, $prefix = '' ) use (&$traverse, &$options, &$selected)
        {
            foreach( $categories as $category )
            {
                // Build option
                $opt = '<option value="'. $category->id .'"';

                if( ! is_null($selected) )
                {
                    if( ! is_array($selected) )
                        $selected = [ $selected ];

                    if( in_array( $category->id, $selected ) )
                        $opt .= ' selected="selected"';
                }

                $opt .= '>'. $prefix .' '. $category->name .'</option>';

                // Add to options array
                $options[] = $opt;

                $traverse( $category->children, $prefix . '--' );
            }
        };

        $traverse( $nodes );

        return implode( '', $options );
    }

    /**
     * Returns the options for Categories select element for First Level
     *
     * @param Collection $nodes
     * @param null|int $selected
     * @return string
     */
    public static function categoriesSelectLevelOne( $nodes, $selected = null )
    {
        $options = [];

        foreach( $nodes as $category )
        {
            // Build option
            $opt = '<option value="'. $category->id .'"';

            if( ! is_null($selected) )
            {
                if( ! is_array($selected) )
                    $selected = [ $selected ];

                if( in_array( $category->id, $selected ) )
                    $opt .= ' selected="selected"';
            }

            $opt .= '> '. $category->name .'</option>';

            // Add to options array
            $options[] = $opt;
        }

        return implode( '', $options );
    }

    /**
     * Builds the Categories checkboxes
     *
     * @param Collection $nodes
     * @param null|int $selected
     * @return void
     */
    public static function categoriesCheckboxes( $nodes, $selected = null )
    {
        $first  = true;

        if( ! is_null($selected) )
        {
            if( ! is_array($selected) )
                $selected = [ $selected ];
        }

        // Recursive function
        $traverse = function( $categories, $level = 0 ) use (&$traverse, &$first, &$selected)
        {
            foreach( $categories as $category )
            {
                // Alias for Category ID and Name
                $id = $category->id;
                $name = $category->name;

                // Build URL
                $url = url('/categoria/' . $category->slug);

                if( $level === 0 )
                {
                    if( ! $first )
                    {
                        echo '</div>';
                    }

                    // Build tags
                    echo '<div class="form-group col-4 category-checks">';
                    echo   '<label class="chk-cat-group" for="">', $name, '</label>';

                    $first = false;
                }
                else
                {
                    $prefix = ($level === 2) ? '-- ' : ' ';
                    $class  = ($level === 2) ? ' chk-cat-lite' : ' chk-cat';

                    echo '<div class="form-check', $class, '">';
                    echo   '<input class="form-check-input" type="checkbox" name="categories[]" value="', $id, '" id="cat-', $id, '"';

                    if( ! is_null($selected) )
                    {
                        if( in_array( $id, $selected ) )
                            echo ' checked="checked"';
                    }
                    echo '>';

                    echo   '<label class="form-check-label" for="cat-', $id, '">';
                    echo     $prefix, $name;
                    echo   '</label>';
                    echo '</div>';
                }

                $traverse( $category->children, $level + 1 );
            }
        };

        $traverse( $nodes );

        // Close last form-row
        echo '</div>';
    }

    // MENUS ======================================================================================
    public static function buildFooterMenus( $categories )
    {
        // Load menus
        $menus = Menu::active()->orderBy('order')->get();

        foreach( $menus as $menu )
        {
            echo '<div class="col-lg-3 col-md-3 col-sm-4">';
            echo   '<div class="footer__widget">';
            echo     '<h6>'. $menu->name .'</h6>';
            echo     '<ul>';

            if( $menu->type === 'categories' )
            {
                foreach( $categories as $cat )
                {
                    if( ! is_null($cat->parent_id) )
                        continue;

                    echo '<li><a href="'. url('/categoria/' . $cat->slug) .'">'. $cat->name .'</a></li>';
                }
            }
            else
            {
                // Get items
                $items = MenuItem::scoped([ 'menu_id' => $menu->id ])->active()->defaultOrder()->get()->toTree();

                foreach( $items as $item )
                {
                    if( $item->target === 'page' )
                    {
                        $page = Page::find( $item->page_id );
                        $link = url( '/pg/' . $page->slug );
                    }
                    else if( $item->target === 'url' )
                    {
                        $link = url( $item->url );
                    }
                    else
                    {
                        $link = $item->url;
                    }

                    echo '<li><a href="'. $link .'" rel="'. $item->page_id .'">'. $item->name .'</a></li>';
                }
            }

            echo     '</ul>';
            echo   '</div>';
            echo '</div>';
        }
    }
}