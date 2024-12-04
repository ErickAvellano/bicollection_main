<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customerName;
    /**
     * Create a new message instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Your Order is Ready for Shipping')
                    ->view('emails.order-ready')
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}
