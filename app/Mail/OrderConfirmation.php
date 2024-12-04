<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $isCustomer;
    public $orderItems;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $order
     * @param  bool   $isCustomer
     * @param  array  $orderItems
     * @return void
     */
    public function __construct($order, $isCustomer, $orderItems)
    {
        $this->order = $order;
        $this->isCustomer = $isCustomer;
        $this->orderItems = $orderItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isCustomer ? 'Your Order Confirmation' : 'New Order Received';

        return $this->subject($subject)
                    ->view('emails.order_confirmation')
                    ->with([
                        'order' => $this->order,
                        'orderItems' => $this->orderItems,
                        'isCustomer' => $this->isCustomer,
                    ]);
    }
}
