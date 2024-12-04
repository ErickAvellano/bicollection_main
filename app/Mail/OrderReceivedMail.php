<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $isMerchant;

    /**
     * Create a new message instance.
     *
     * @param $order
     * @param bool $isMerchant
     */
    public function __construct($order, $isMerchant = false)
    {
        $this->order = $order;
        $this->isMerchant = $isMerchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isMerchant
            ? 'Order Delivered and Received by Customer - BiCollection'
            : 'Order Successfully Received - BiCollection';

        // Load orderItems with product details from the order
        $orderItems = $this->order->orderItems()->with('product')->get(); // Ensure the orderItems are fetched

        return $this->subject($subject)
                    ->view('emails.order_received')
                    ->with([
                        'order' => $this->order,
                        'orderItems' => $orderItems,  // Pass orderItems to the view
                        'isMerchant' => $this->isMerchant,
                    ]);
    }
}
