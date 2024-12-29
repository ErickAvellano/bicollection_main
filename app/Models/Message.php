<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    // Specify the table if it's not the plural of the model name
    protected $table = 'messages';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'chat_id',
        'sender_id',
        'receiver_id',
        'message',
        'message_type',
    ];
    
    // Define the relationship to the chat
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }


}
