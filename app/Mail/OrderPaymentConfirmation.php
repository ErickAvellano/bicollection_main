<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $isCustomer;
    public $orderItems;
    public $customerName;
    public $merchantName;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $order
     * @param  bool   $isCustomer
     * @param  mixed  $orderItems
     */
    public function __construct($order, $isCustomer, $orderItems)
    {
        $this->order = $order;
        $this->isCustomer = $isCustomer;
        $this->orderItems = $orderItems; // Convert to array

        // Set the names from related models
        $this->customerName = $order->customer->name ?? 'Customer'; // Full name for customer
        $this->merchantName = $order->merchant->username ?? 'Merchant'; // Use username for merchant
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isCustomer
            ? 'Payment Confirmation for Your Order'
            : 'New GCash Payment Received';

        $view = $this->isCustomer
            ? 'emails.order_confirmation_customer'
            : 'emails.order_confirmation_merchant';

        return $this->subject($subject)
                    ->view($view)
                    ->with([
                        'order' => $this->order,
                        'orderItems' => $this->orderItems,
                        'customerName' => $this->customerName,
                        'merchantName' => $this->merchantName,
                    ]);
    }
}
