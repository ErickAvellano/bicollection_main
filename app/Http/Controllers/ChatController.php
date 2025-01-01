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

        // Try to find the existing chat
        $chat = Chat::where(function ($query) use ($customerId, $adminID) {
            $query->where('customer_id', $customerId)
                ->where('admin_id', $adminID);
        })->first();

        if (!$chat) {
            // If no existing chat, create a new one
            $chat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => $adminID,
            ]);
        }

        // Ensure $chat and $chat->chat_id exist
        if (!$chat || !$chat->chat_id) {
            return response()->json(['error' => 'Unable to retrieve or create chat'], 500);
        }

        $chatId = $chat->chat_id;

        // Get all messages for the given chat_id
        $messages = Message::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc') // Order messages by creation time
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
                        : 'https://via.placeholder.com/40', // Optional sender avatar
                ];
            });

        return response()->json($messages);
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
        // Log the incoming request for debugging
        Log::info('Received customer to admin message request', [
            'customerId' => $request->input('customerId'),
            'problem' => $request->input('problem')
        ]);

        // Validate the incoming request
        $request->validate([
            'customerId' => 'required|integer|exists:customer,customer_id',
            'problem' => 'required|string|max:255',
        ]);

        $adminID = 63; // Replace with your admin ID logic if dynamic
        $customerId = $request->input('customerId');
        $problem = $request->input('problem');

        // Log the admin and customer details
        Log::info('Admin ID and Customer ID details', [
            'admin_id' => $adminID,
            'customer_id' => $customerId,
        ]);

        // Check if there's an existing chat
        $chat = Chat::where('customer_id', $customerId)->where('admin_id', $adminID)->first();

        // Log whether a chat exists or not
        if ($chat) {
            Log::info('Found existing chat', ['chat_id' => $chat->chat_id]);
        } else {
            Log::info('No existing chat found, creating new chat');
        }

        // Create a new chat if none exists
        if (!$chat) {
            $chat = Chat::create([
                'customer_id' => $customerId,
                'admin_id' => $adminID,
            ]);
            Log::info('New chat created', ['chat_id' => $chat->chat_id]);
        }

        // Ensure chat_id is valid before proceeding to create a message
        if (!$chat || !$chat->chat_id) {
            Log::error('Chat creation failed or chat_id is missing', ['chat_data' => $chat]);
            return response()->json(['success' => false, 'message' => 'Chat creation failed.']);
        }

        $chatID = $chat->chat_id;

        // Create a new message with the problem
        $message = new Message();
        $message->chat_id = $chatID;
        $message->sender_id = $chat->customer_id;
        $message->receiver_id = $chat->admin_id;
        $message->message = $request->input('problem');
        $message->save();

        // Log the message creation
        Log::info('New message created', [
            'message_id' => $message->message_id,
            'chat_id' => $chatID,
            'message' => $problem,
        ]);

        // Return the response
        return response()->json([
            'success' => true,
            'chat_id' => $chatID,
            'message_id' => $message->message_id,
        ]);
    }


















}
