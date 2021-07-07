<?php
namespace App\Services\Traits;

use Carbon\Carbon;

trait GenericAttributeAcessorsTrait
{
    /**
     * Returns the Created At attribute formatted properly.
     * @param Carbon $value
     * @return string
     */
    public function getFullDateAttribute( $value )
    {
        // Convert to Carbon if need
        if( ! is_a($value, 'Carbon') )
        {
            $tzone = config('app.timezone');
            $value = Carbon::parse( $value, $tzone );
        }
        
        return $value->format('d.m.Y - H:i:s');
    }
    
    /**
     * Returns the Updated At attribute formatted properly.
     * @param Carbon $value
     * @return string
     */
    public function getUpdatedFullAttribute( $value )
    {
        // Convert to Carbon if need
        if( ! is_a($value, 'Carbon') )
        {
            $tzone = config('app.timezone');
            $value = Carbon::parse( $value, $tzone );
        }
        
        return $value->format('d.m.Y - H:i:s');
    }
    
    /**
     * Returns the special Date attribute with human readable format.
     * @param mixed $value
     * @return string
     */
    public function getHumanDateAttribute( $value )
    {
        //$loc = setlocale(LC_TIME, 'ptb', 'portuguese-brazilian', 'bra', 'brazil', 'pt-br', 'pt_BR', 'pt_BR.UTF8');
        
        //dd( $loc );
        
        // Convert to Carbon if need
        if( ! is_a($value, 'Carbon') )
        {
            $tzone = config('app.timezone');
            $value = Carbon::parse( $value, $tzone );
        }
        
        return ucwords( $value->formatLocalized('%d %b %Y - %A') );
    }
    
    /**
     * Returns the special Simple Date attribute.
     * @param mixed $value
     * @return string
     */
    public function getSimpleDateAttribute( $value )
    {
        // Convert to Carbon if need
        if( ! is_a($value, 'Carbon') )
        {
            $tzone = config('app.timezone');
            $value = Carbon::parse( $value, $tzone );
        }
        
        return $value->format('d.m.Y');
    }
}