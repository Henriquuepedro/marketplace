<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
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
        return $this->replyTo( $this->data->email )
                ->subject('Nova mensagem de Fale Conosco')
                ->markdown('emails.keewe.contact')
                ->with([
                    'name'    => $this->data->name,
                    'email'   => $this->data->email,
                    'city'    => $this->data->city,
                    'state'   => $this->data->state,
                    'message' => $this->data->message
                ]);
    }
}
