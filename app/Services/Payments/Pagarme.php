<?php
namespace App\Services\Payments;

use PagarMe\Client;

/**
 * The PagarMe class
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 1.0
 * @copyright (c) 2020
 */
class Pagarme
{
    protected static $url = 'https://api.pagar.me/1/';

    /**
     * Creates a Recipient with Bank Account
     *
     * @param StoreInfo $store_info
     * @return \stdClass
     */
    public static function addRecipient( $store_info )
    {
        $api_key = self::getApiKey();
        $pagarme = new Client( $api_key );

        $recipient = $pagarme->recipients()->create([
            'transfer_interval' => 'daily',
            'transfer_enabled'  => true,
            'bank_account'      => [
                'bank_code'       => $store_info->bank->code,
                'agencia'         => $store_info->bank_branch,
                'agencia_dv'      => $store_info->bank_branch_dv,
                'conta'           => $store_info->bank_account,
                'conta_dv'        => $store_info->bank_account_dv,
                'type'            => $store_info->bank_account_type,
                'document_number' => $store_info->account_holder_doc,
                'legal_name'      => $store_info->account_holder_name
            ],
        ]);

        if( ! $recipient )
        {
            return self::error( 'An unknown error has occurred' );
        }

        if( isset($recipient->errors) )
        {
            return self::error( $recipient->errors[0]->message );
        }

        // Data to be returned
        return (object) [
            'success' => true,
            'recipient_id' => $recipient->id,
            'status' => $recipient->status,
            'reason' => $recipient->status_reason,
            'bank_account_id' => $recipient->bank_account->id
        ];
    }

    /**
     * Creates a Transaction
     *
     * @param array $transaction_data
     * @return \stdClass
     */
    public static function addTransaction( $transaction_data )
    {
        $api_key = self::getApiKey();
        $pagarme = new Client( $api_key );

        $transaction = $pagarme->transactions()->create( $transaction_data );

        if( ! $transaction )
        {
            return self::error( 'An unknown error has occurred' );
        }

        if( isset($transaction->errors) )
        {
            return self::error( $transaction->errors[0]->message );
        }

        // Data to be returned
        return (object) [
            'success' => true,
            'transaction' => $transaction
        ];
    }

    // ==================================================================================
    /**
     * Returns correct API Key according to environment
     *
     * @return string
     */
    public static function getApiKey()
    {
        // Get env
        $env = env('PAGARME_ENVIRONMENT');

        return env('PAGARME_'. $env .'_API_KEY');
    }

    /**
     * Returns correct Encryptation Key according to environment
     *
     * @return string
     */
    public static function getEncKey()
    {
        // Get env
        $env = env('PAGARME_ENVIRONMENT');

        return env('PAGARME_'. $env .'_CRYPT_KEY');
    }

    /**
     * Returns correct Site's Recipient ID according to environment
     *
     * @return string
     */
    public static function getSiteRecipientID()
    {
        // Get env
        $env = env('PAGARME_ENVIRONMENT');

        return env('PAGARME_'. $env .'_SITE_RECIPIENT_ID');
    }

    /**
     * Returns an error
     *
     * @param string $message
     * @return \stdClass
     */
    public static function error( $message )
    {
        return (object)[
            'success' => false,
            'error'   => $message
        ];
    }
}
