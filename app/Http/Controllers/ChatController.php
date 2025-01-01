<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chat;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ChatController extends Controller
{
    public function fetchInquiries()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure only admin users can access this method
        if ($user->type !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Fetch inquiries where chats are sent to the admin, including the latest message
        $inquiries = Chat::with(['customer.customerImage', 'latestMessage'])
            ->where('admin_id', $user->user_id)
            ->get()
            ->map(function ($chat) {
                $latestMessage = Message::where('chat_id', $chat->chat_id)
                ->orderBy('created_at', 'desc')
                ->first();
                $avatarPath = $chat->customer?->customerImage?->img_path;

                return [
                    'chat_id' => $chat->chat_id,
                    'customer_id' => $chat->customer?->customer_id,
                    'customer_name' => $chat->customer?->username,
                    'customer_avatar' => $avatarPath ? asset('/storage/'.$avatarPath) : null,
                    'last_message' => $latestMessage?->message,
                    'last_message_time' => $latestMessage ? $latestMessage->created_at : null,
                ];
            });
        // Return the inquiries as JSON
        return response()->json($inquiries);
    }
    public function fetchMerchants()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure only admin users can access this method
        if ($user->type !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Fetch all merchants and their shops
        $shops = Shop::with('merchant') // Load the associated merchant
            ->get()
            ->map(function ($shop) use ($user) {
                // Find the chat between the merchant and the admin
                $chat = Chat::where('merchant_id', $shop->merchant_id)
                    ->where('admin_id', $user->user_id)
                    ->first();

                // Retrieve the latest message, if a chat exists
                $latestMessage = null;
                if ($chat) {
                    $latestMessage = Message::where('chat_id', $chat->chat_id)
                        ->where('sender_id', $shop->merchant_id) // Merchant is the sender
                        ->where('receiver_id', $user->user_id) // Admin is the receiver
                        ->orderBy('created_at', 'desc')
                        ->first();
                }

                return [
                    'shop_id' => $shop->shop_id,
                    'merchant_id' => $shop->merchant_id,
                    'shop_name' => $shop->shop_name ?? 'Unknown Shop',
                    'shop_img' => $shop->shop_img ? asset('/storage/' . $shop->shop_img) : 'https://via.placeholder.com/150',
                    'last_message' => $latestMessage?->message,
                    'last_message_time' => $latestMessage ? $latestMessage->created_at : null,
                ];
            });

        // Return the merchants as JSON
        return response()->json($shops);
    }
    public function getChatMessages($chatId)
    {
        try {

            // Get all messages for the given chat_id
            $messages = Message::where('chat_id', $chatId)
                ->orderBy('created_at', 'asc') // Order messages by creation time
                ->get()
                ->map(function ($message) {
                    return [
                        'message_id' => $message->message_id,
                        'chat_id' => $message->chat_id,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id,
                        'message' => $message->message,
                        'created_at' => $message->created_at,
                        'sender_avatar' => $message->sender?->avatar_path ? asset('/storage/' . $message->sender->avatar_path) : 'https://via.placeholder.com/40', // Optional sender avatar
                    ];
                });

            return response()->json($messages);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch messages'], 500);
        }
    }
    public function getMessagesByCustomer($customerId)
    {
        $adminID = 63;

        // Find chat or return a default response
        $chat = Chat::where(function ($query) use ($customerId, $adminID) {
            $query->where('customer_id', $customerId)
                ->where('admin_id', $adminID);
        })->first();

        $chatId = $chat?->chat_id ?? null;
        if (!$chatId) {
            return response()->json(['success' => false, 'message' => 'No chat found']);
        }

        // Get all messages for the given chat_id
        $messages = Message::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($adminID) {
                return [
                    'message_id' => $message->message_id,
                    'chat_id' => $message->chat_id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $adminID,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'sender_avatar' => $message->sender?->avatar_path
                        ? asset('/storage/' . $message->sender->avatar_path)
                        : 'https://via.placeholder.com/40',
                ];
            });

        return response()->json(['success' => true, 'messages' => $messages, 'chat_id' => $chatId]);
    }

    // Method to send a message Admin
    public function sendMessage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'message' => 'required|string|max:255',
            'chatId' => 'required|integer|exists:chats,chat_id',
        ]);

        // Process the message
        $chat = Chat::find($request->input('chatId'));

        if (!$chat) {
            return response()->json(['success' => false, 'message' => 'Chat not found.']);
        }

        $message = new Message();
        $message->chat_id = $request->input('chatId');
        $message->sender_id = $chat->admin_id;
        $message->receiver_id = $chat->customer_id;
        $message->message = $request->input('message');
        $message->save();

        // Return success response
        return response()->json(['success' => true, 'message' => 'Message sent successfully.']);
    }
    //customer to admin
    public function customertoadminmessage(Request $request)
    {
        if (!Auth::check() || Auth::user()->type !== 'customer') {
            return redirect()->route('login')->with('error', 'Unauthorized access. Only merchants can view this page.');
        }
        // Validate the incoming request
        $request->validate([
            'message' => 'required|string|max:255',
            'customerId' => 'required|integer|exists:customer,customer_id',
        ]);

        // Retrieve or create the chat
        $customerId = $request->input('customerId');
        $chat = Chat::where('customer_id', $customerId)->first();

        if (!$chat) {
            // No existing chat, create a new one
            $chat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => 63
            ]);
        }

        // Create the message
        $message = new Message();
        $message->chat_id = $chat->chat_id;
        $message->sender_id = $customerId; // Customer as sender
        $message->receiver_id = $chat->admin_id;  // Admin as receiver
        $message->message = $request->input('message');
        $message->save();

        return response()->json(['success' => true, 'message' => 'Message sent successfully.']);
    }
    public function startcustomertoadminmessage(Request $request)
    {
        if (!Auth::check() || Auth::user()->type !== 'customer') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Validate the incoming request
        $validated = $request->validate([
            'customerId' => 'required|integer|exists:customer,customer_id',  
            'problem' => 'required|string|max:255',
        ]);

        $adminID = 63; // Replace with your admin ID logic if dynamic
        $customerId = $request->input('customerId');
        $problem = $request->input('problem');

        // Check if there's an existing chat
        $chat = Chat::where('customer_id', $customerId)->where('admin_id', $adminID)->first();

        // Create a new chat if none exists
        if (!$chat) {
            $chat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => $adminID,
            ]);
            $chatID = $chat->chat_id;
        } else {
            $chatID = $chat->chat_id; // If the chat already exists, use its chat ID
        }

        // Create a new message (customer's message)
        $message = new Message();
        $message->chat_id = $chatID;
        $message->sender_id = $chat->customer_id; 
        $message->receiver_id = $chat->admin_id; 
        $message->message = "Customer has started a chat on topic: " . $request->problem;
        $message->save(); // Save the customer's message

        sleep(3); 

        // Admin's automated reply
        $adminReply = new Message();
        $adminReply->chat_id = $chat->chat_id;
        $adminReply->sender_id = $chat->admin_id;
        $adminReply->receiver_id =  $chat->customer_id;
        $adminReply->message = "Hello! Can you describe your problem so that we can assist you better?";
        $adminReply->save(); // Save the admin's reply

        // Return the response with the chat ID
        return response()->json([
            'success' => true,
            'chat_id' => $chatID,
            'message' => $message,
        ]);
    }

    public function startliveChat(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
        ]);
    
        if (!Auth::check() || Auth::user()->type !== 'customer') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
    
        $customerId = Auth::id();
        $adminID = 63; // Replace this with your admin ID logic
        $chat = Chat::where('customer_id', $customerId)->where('admin_id', $adminID)->first();
    
        if (!$chat) {
            $chat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => $adminID,
                'topic_id' => $request->topic,
            ]);
        }
    
        // Create the customer's message
        $message = new Message();
        $message->chat_id = $chat->chat_id;
        $message->sender_id = $customerId;
        $message->receiver_id = $adminID;
        $message->message = "Customer has started a chat on topic: " . $request->topic;
        $message->save();
    
        // Admin's automated reply
        $adminReply = new Message();
        $adminReply->chat_id = $chat->chat_id;
        $adminReply->sender_id = $adminID;
        $adminReply->receiver_id = $customerId;
        $adminReply->message = "Hello! Can you describe your problem so that we can assist you better?";
        $adminReply->save();
    
        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Chat started successfully!',
            'chat_id' => $chat->chat_id,
        ]);
    }
    
    
        
        

}
