<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $fillable = ['user_id', 'email', 'otp', 'expires_at'];

    public $timestamps = false;

    public function isExpired()
    {
        return Carbon::parse($this->expires_at)->isPast();
    }
}
