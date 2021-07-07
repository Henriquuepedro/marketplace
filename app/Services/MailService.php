<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;

use App\Mail\ContactMail;
use App\Mail\ValidateUser;
use App\Mail\PasswordReset;
//use App\Mail\OrderPlaced;
use App\Mail\OrderToCustomer;
use App\Mail\OrderToVendor;
use App\Mail\OrderShipped;
use App\Mail\OrderOccurrence;

/**
 * Class responsible to send emails
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2021, Keewe
 */
class MailService
{
    /**
     * Indicates the debug mode
     *
     * @var boolean
     */
    public static $debug = false;

    /**
     * Dispatch the Contact Mail
     *
     * @param Contact $contact_model
     * @return void
     */
    public static function sendContact( $contact_model )
    {
        // Get mail data
        $msg_data = (object)[
            'name'    => $contact_model->name,
            'email'   => $contact_model->email,
            'city'    => $contact_model->city,
            'state'   => $contact_model->state,
            'message' => $contact_model->message
        ];

        // Create message instance
        $message = new ContactMail( $msg_data );

        // Get receiver
        $receiver = env('CONTACT_RECEIVER');

        if( self::$debug )
            return $message->render();

        Mail::to( $receiver )->send( $message );
    }

    /**
     * Dispatch the Mail with account validation link
     *
     * @param string $fullname
     * @param string $username
     * @param string $token
     * @return void
     */
    public static function sendRegister( string $fullname, string $username, string $token )
    {
        // Build mail data
        $mail_data = (object) [
            'fullname' => $fullname,
            'link' => url('/users/validate?token=' . $token)
        ];

        // Create message instance
        $message = new ValidateUser( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Password Reset mail
     *
     * @param string $username
     * @param string $token
     * @return void
     */
    public static function sendPasswordReset( string $username, string $token )
    {
        // Build mail data
        $mail_data = (object) [
            //'fullname' => $fullname,
            'token' => $token
        ];

        // Create message instance
        $message = new PasswordReset( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Mail with New Order
     *
     * @param string $fullname
     * @param string $username
     * @param Order $order
     * @param Array $items
     * @return void
     */
    public static function sendOrderPlaced( string $fullname, string $username, $order, $items )
    {
        // Build mail data
        $mail_data = (object)[
            'fullname' => $fullname,
            'order' => $order,
            'items' => $items,
            'link' => url('/meus-pedidos')
        ];

        // Create message instance
        $message = new OrderPlaced( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Mail with New Order to Customer
     *
     * @param string $fullname
     * @param string $username
     * @param Order $order
     * @param Array $items
     * @return void
     */
    public static function sendOrderToCustomer( string $fullname, string $username, $order, $items )
    {
        // Build mail data
        $mail_data = (object)[
            'fullname' => $fullname,
            'order' => $order,
            'items' => $items,
            'link' => url('/meus-pedidos')
        ];

        // Create message instance
        $message = new OrderToCustomer( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Mail with New Order to Vendor
     *
     * @param string $fullname
     * @param string $username
     * @param Order $order
     * @param Array $items
     * @return void
     */
    public static function sendOrderToVendor( string $fullname, string $username, $order )
    {
        // Build mail data
        $mail_data = (object)[
            'fullname' => $fullname,
            'order' => $order,
            'link' => url('/pedidos')
        ];

        // Create message instance
        $message = new OrderToVendor( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Mail with Order Shipping info
     *
     * @param string $fullname
     * @param string $username
     * @param Order $order
     * @return void
     */
    public static function sendOrderShipped( string $fullname, string $username, $order )
    {
        // Build mail data
        $mail_data = (object)[
            'fullname' => $fullname,
            'order' => $order
        ];

        // Create message instance
        $message = new OrderShipped( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $username )->send( $message );
    }

    /**
     * Dispatch the Mail with Order Occurrence to vendor
     *
     * @param string $mail_to
     * @param \stdClass $mail_data
     * @return void
     */
    public static function sendOrderOccurrence( $mail_to, $mail_data )
    {
        // Create the message
        $message = new OrderOccurrence( $mail_data );

        if( self::$debug )
            return $message->render();

        Mail::to( $mail_to )->send( $message );
    }
}