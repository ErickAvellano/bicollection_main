<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order; // Assuming the Order model is related to refund requests

class RefundRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $merchant;

    /**
     * Create a new message instance.
     *
     * @param $order
     * @param $merchant
     */
    public function __construct($order, $merchant)
    {
        $this->order = $order;
        $this->merchant = $merchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Refund Request - Action Required')
                    ->view('emails.refund_request')
                    ->with([
                        'order' => $this->order,
                        'merchant' => $this->merchant,
                    ]);
    }
}

