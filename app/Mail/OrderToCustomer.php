<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderToCustomer extends Mailable
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
        // Calculate product quantity
        $pdt_num = 0;

        foreach( $this->data->items as $item )
        {
            $pdt_num += $item->quantity;
        }

        return $this->subject('Keewe - Seu pedido')
                ->markdown('emails.keewe.order_customer')
                ->with([
                    'fullname' => $this->data->fullname,
                    'order' => $this->data->order,
                    'items' => $this->data->items,
                    'count' => $pdt_num,
                    'link' => $this->data->link,
                ]);
    }
}
