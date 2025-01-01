<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CustomerImage;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;



class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'user';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'username',
        'password',
        'type',
        'email_verified',
        'verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'type' => 'string',
        'email_verified' => 'boolean',
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
        'last_login' => 'datetime',
    ];
    public $timestamps = false;

    /**
     * Generate a random and unique username.
     *
     * @return string
     */
    public static function generateRandomUsername()
    {
        do {
            $username = 'user' . rand(10000, 99999);
        } while (self::where('username', $username)->exists());

        return $username;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        Mail::send('emails.password-reset', ['resetUrl' => $resetUrl], function ($message) {
            $message->to($this->email);
            $message->subject('Reset Your Password');
        });
    }


    public function customerImage()
    {
        return $this->hasOne(CustomerImage::class, 'customer_id', 'customer_id');
    }
        // In app/Models/User.php
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'user_id');
    }
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'customer_id');
    }
    public function isMerchant()
    {
        return $this->type === 'merchant'; 
    }
    public function isCustomer()
    {
        return $this->type === 'customer';
    }
    public function shop()
    {
        return $this->hasOne(Shop::class, 'merchant_id', 'user_id');
    }
    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'user_id');
    }
    public function isAdmin()
    {
        return $this->type === 'admin'; 
    }
    public function messagesAsSender()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }



}
