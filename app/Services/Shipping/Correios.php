<?php
namespace App\Services\Shipping;

use App\Services\ApiService;
use App\Services\AppMessage;

/**
 * The Correios class
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 1.0
 * @copyright (c) 2020
 */
class Correios
{
    protected static $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo';
    protected $error;

    const MIN_WIDTH = 10;
    const MAX_WIDTH = 100;

    const MIN_HEIGHT = 10;
    const MAX_HEIGHT = 100;

    const MIN_LENGTH = 15;
    const MAX_LENGTH = 100;

    const MAX_WEIGHT = 30;

    /**
     * Calculates and returns the shipping from given product properties
     *
     * @param string $origin
     * @param string $destiny
     * @param float $width
     * @param float $height
     * @param float $length
     * @param float $weight
     * @return \stdClass
     */
    public static function calculate( $origin, $destiny, $width, $height, $length, $weight )
    {
        // First, convert numbers
        $width  = (float) $width;
        $height = (float) $height;
        $length = (float) $length;
        $weight = to_kilo( $weight );

        // Validate limits
        $valid = self::validateLimits( $width, $height, $length, $weight );

        //dd( $valid instanceof \App\Services\AppMessage );

        if( $valid instanceof \App\Services\AppMessage )
        {
            return $valid;
        }

        // Data to be sent
        $payload = [
            //'op'                  => 'CalcPrecoPrazo',
            'nCdEmpresa'          => env('CORREIOS_COMPANY') ?? '',
            'sDsSenha'            => env('CORREIOS_PASSWORD') ?? '',
            'nCdServico'          => env('CORREIOS_SERVICE') ?? '04014',
            'sCepOrigem'          => preg_replace('/[^0-9]/', '', $origin),
            'sCepDestino'         => preg_replace('/[^0-9]/', '', $destiny),
            'nVlPeso'             => $weight,
            'nCdFormato'          => 1,
            'nVlComprimento'      => $length,
            'nVlAltura'           => $width,
            'nVlLargura'          => $height,
            'nVlDiametro'         => 0,
            'sCdMaoPropria'       => 'N',
            'nVlValorDeclarado'   => 0,
            'sCdAvisoRecebimento' => 'N'
        ];

        //dd( $payload );

        // Consume web service
        $result = ApiService::post( self::$url, $payload, null, ApiService::FORMAT_RAW );
        $json   = xml_to_json( $result );

        // Check result
        if( ! isset($json->Servicos) )
            return new AppMessage( false, 'Ocorreu uma falha ao calcular os valores de frete.' );

        if( ! isset($json->Servicos->cServico) )
            return new AppMessage( false, 'Ocorreu uma falha ao calcular os valores de frete.' );

        return $json->Servicos->cServico;
    }

    /**
     * Validates limits
     *
     * @param float $width
     * @param float $height
     * @param float $length
     * @param float $weight
     * @return AppMessage|bool
     */
    public static function validateLimits( $width, $height, $length, $weight )
    {
        // WIDTH --------------------------------------------------------------
        // MIN_WIDTH
        if( ($width < self::MIN_WIDTH) )
        {
            return new AppMessage( false, 'A largura é menor do que o limite dos Correios, de '. self::MIN_WIDTH .' cm' );
        }

        // MAX_WIDTH
        if( ($width > self::MAX_WIDTH) )
        {
            return new AppMessage( false, 'A largura é maior do que o limite dos Correios, de '. self::MAX_WIDTH .' cm' );
        }

        // HEIGHT -------------------------------------------------------------
        // MIN_HEIGHT
        if( ($height < self::MIN_HEIGHT) )
        {
            return new AppMessage( false, 'A altura é menor do que o limite dos Correios, de '. self::MIN_HEIGHT .' cm' );
        }

        // MAX_HEIGHT
        if( ($height > self::MAX_HEIGHT) )
        {
            return new AppMessage( false, 'A altura é maior do que o limite dos Correios, de '. self::MAX_HEIGHT .' cm' );
        }

        // LENGTH -------------------------------------------------------------
        // MIN_LENGTH
        if( ($length < self::MIN_LENGTH) )
        {
            return new AppMessage( false, 'O comprimento é menor do que o limite dos Correios, de '. self::MIN_LENGTH .' cm' );
        }

        // MAX_LENGTH
        if( ($length > self::MAX_LENGTH) )
        {
            return new AppMessage( false, 'O comprimento é maior do que o limite dos Correios, de '. self::MAX_LENGTH .' cm' );
        }

        // WEIGHT -------------------------------------------------------------
        // MAX_WEIGHT
        if( ($weight > self::MAX_WEIGHT) )
        {
            return new AppMessage( false, 'O peso é maior do que o limite dos Correios, de '. self::MAX_WEIGHT .' kg' );
        }

        return true;
    }
}
