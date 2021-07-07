<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data )
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Keewe - Seu pedido está a caminho')
                ->markdown('emails.keewe.order_shipped')
                ->with([
                    'fullname' => $this->data->fullname,
                    'order' => $this->data->order,
                    'json' => json_decode( $this->data->order->request_data ),
                ]);
    }
}
