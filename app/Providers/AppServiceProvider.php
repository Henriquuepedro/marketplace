<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // CPF Validation rule ====================================================================
        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator)
        {
            $cpf_str = $value;

            if( empty($cpf_str) || is_null($cpf_str) || ($cpf_str == false) )
                return false;
            
            // Clean other chars
            $cpf = preg_replace('/[^0-9]/', '', $cpf_str);
            $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
            
            if( strlen($cpf) != 11 )
                return false;

            if( ($cpf == '00000000000') || ($cpf == '11111111111') || ($cpf == '22222222222') ||
                    ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') ||
                    ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') ||
                    ($cpf == '99999999999') || ($cpf == '12345678901') || ($cpf == '01234567890'))
            {
                return false;
            }
            
            for( $t = 9; $t < 11; $t++ )
            {
                for( $d = 0, $c = 0; $c < $t; $c++ )
                {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                
                $d = ((10 * $d) % 11) % 10;
                
                if( $cpf[$c] != $d )
                    return false;
            }
            
            return true;
        });

        // CNPJ Validation rule ===================================================================
        Validator::extend('cnpj', function ($attribute, $value, $parameters, $validator)
        {
            $cnpj = preg_replace("/[^0-9]/", "", $value);

            if( (strlen($cnpj) != 14) || ! is_numeric($cnpj) )
                return false;
            
            if( $cnpj == '00000000000000' )
                return false;

            $j = 5;
            $k = 6;
            $sum1 = 0;
            $sum2 = 0;

            for($i = 0; $i < 13; $i++)
            {
                $j = (($j == 1) ? 9 : $j);
                $k = (($k == 1) ? 9 : $k);

                $sum2 += ($cnpj[$i] * $k);

                if( $i < 12 )
                {
                    $sum1 += ($cnpj[$i] * $j);
                }

                $k--;
                $j--;
            }

            $digit1 = ( (($sum1 % 11) < 2) ? 0 : (11 - $sum1 % 11) );
            $digit2 = ( (($sum2 % 11) < 2) ? 0 : (11 - $sum2 % 11) );

            if( !(($cnpj[12] == $digit1) && ($cnpj[13] == $digit2)) )
                return false;

            return true;
        });
		
		// DOC Validation rule (CPF or CNPJ) ======================================================
		Validator::extend('doc', function ($attribute, $value, $parameters, $validator)
        {
			$doc = $value;

            if( empty($doc) || is_null($doc) || ($doc == false) )
                return false;
			
			// Clear chars (keep numbers only)
			$doc = preg_replace('/[^0-9]/', '', $doc);
			
			// Check lenght
			if( strlen($doc) <= 11 )
			{
				// CPF Validation -----------------------------------------------------------------
				$doc = str_pad($doc, 11, '0', STR_PAD_LEFT);
				
				if( ($doc == '00000000000') || ($doc == '11111111111') || ($doc == '22222222222') ||
                    ($doc == '33333333333') || ($doc == '44444444444') || ($doc == '55555555555') ||
                    ($doc == '66666666666') || ($doc == '77777777777') || ($doc == '88888888888') ||
                    ($doc == '99999999999') || ($doc == '12345678901') || ($doc == '01234567890'))
				{
					return false;
				}
				
				for( $t = 9; $t < 11; $t++ )
				{
					for( $d = 0, $c = 0; $c < $t; $c++ )
					{
						$d += $doc[$c] * (($t + 1) - $c);
					}
					
					$d = ((10 * $d) % 11) % 10;
					
					if( $doc[$c] != $d )
						return false;
				}
				
				return true;
			}
			else
			{
				// CNPJ Validation ----------------------------------------------------------------
				$doc = str_pad($doc, 14, '0', STR_PAD_LEFT);
				
				if( ($doc == '00000000000000') || ($doc == '11111111111111') || ($doc == '22222222222222') ||
                    ($doc == '33333333333333') || ($doc == '44444444444444') || ($doc == '55555555555555') ||
                    ($doc == '66666666666666') || ($doc == '77777777777777') || ($doc == '88888888888888') ||
                    ($doc == '99999999999999') )
				{
					return false;
				}
				
				$j = 5;
				$k = 6;
				$sum1 = 0;
				$sum2 = 0;

				for($i = 0; $i < 13; $i++)
				{
					$j = (($j == 1) ? 9 : $j);
					$k = (($k == 1) ? 9 : $k);

					$sum2 += ($doc[$i] * $k);

					if( $i < 12 )
					{
						$sum1 += ($doc[$i] * $j);
					}

					$k--;
					$j--;
				}

				$digit1 = ( (($sum1 % 11) < 2) ? 0 : (11 - $sum1 % 11) );
				$digit2 = ( (($sum2 % 11) < 2) ? 0 : (11 - $sum2 % 11) );

				if( !(($doc[12] == $digit1) && ($doc[13] == $digit2)) )
					return false;
				
				return true;
			}
        });

        // Accepted If ============================================================================
        Validator::extend('accepted_if', function ($attribute, $value, $parameters, $validator)
        {
            //dump( $attribute, $value, $parameters, $validator );

            // Get related input
            $rel = request()->input( $parameters[0] );

            if( ($rel == 'yes') && ($value != 'yes') )
                return false;

            return true;
        });
    }
}
