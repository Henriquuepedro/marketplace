<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderOccurrence extends Mailable
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
        return $this->subject('Keewe - Nova solicitação de cliente')
                ->markdown('emails.keewe.order_occurrence')
                ->with([
                    'fullname' => $this->data->fullname,
                    'order_tid' => $this->data->order_tid,
                    'occurrence' => $this->data->occurrence
                ]);
    }
}
