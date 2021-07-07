<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderToVendor extends Mailable
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
        return $this->subject('Keewe - Novo pedido')
                ->markdown('emails.keewe.order_vendor')
                ->with([
                    'fullname' => $this->data->fullname,
                    'order' => $this->data->order,
                    'link' => $this->data->link,
                ]);
    }
}
