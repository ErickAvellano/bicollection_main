<?php

namespace App\Observers;

use App\Models\Message;
use App\Models\Chat;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message)
    {
        // Ensure the message has a sender and that it's from the customer
        if ($message->sender && $message->sender->type === 'customer' && strpos($message->message, 'Customer has started') === false) {
            // Find the chat details
            $chat = Chat::find($message->chat_id);

            // Check if an admin reply already exists in the chat
            $existingReply = Message::where('chat_id', $chat->chat_id)
                                    ->where('sender_id', $chat->admin_id)
                                    ->where('message', "Got it! Please wait for the admin's response.")
                                    ->first();

            // If no reply exists, send the admin reply
            if (!$existingReply) {
                $adminReply = new Message();
                $adminReply->chat_id = $chat->chat_id;
                $adminReply->sender_id = $chat->admin_id;
                $adminReply->receiver_id = $chat->customer_id;
                $adminReply->message = "Got it! Please wait for the admin's response.";
                $adminReply->save();
            }
        }

    }

    

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
