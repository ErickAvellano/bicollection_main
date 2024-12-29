<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Chat extends Model
{
    // Specify the table if it's not the plural of the model name
    protected $table = 'chats';

        // Define fillable attributes for mass assignment
    protected $primaryKey = 'chat_id'; 

    protected $fillable = [
        'merchant_id',
        'customer_id',
        'admin_id',
    ];

    // Define the relationship to messages
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'chat_id')->latest('created_at');
    }
    // Define relationships to Merchant, Customer, and Admin models if they exist
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function admin()
{
    return $this->belongsTo(User::class, 'user_id')->where('type', 'admin');
}
}
