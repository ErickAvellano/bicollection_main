<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantMop extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'merchant_mop';

    // Specify the primary key column
    protected $primaryKey = 'merchant_mop_id';

    // Indicates if the IDs are auto-incrementing
    public $incrementing = true;

    // Set the primary key type
    protected $keyType = 'int';

    // Enable timestamps for created_at (if using timestamps)
    public $timestamps = true;

    // Allow mass assignment for these columns
    protected $fillable = [
        'merchant_id',
        'account_type',
        'cod_terms_accepted',
        'description',
        'account_name',
        'account_number',
        'gcash_qr_code', // Add this if GCash QR code is also managed here
        'gcash_number',
        'created_at',
    ];

    /**
     * Define relationships with other models
     */

    // Relationship to the Merchant model
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    /**
     * Accessors and Mutators
     */

    // Accessor for formatted account type
    public function getFormattedAccountTypeAttribute()
    {
        return ucfirst($this->account_type);
    }

    // Accessor for formatted account number (e.g., masking the middle digits)
    public function getMaskedAccountNumberAttribute()
    {
        $length = strlen($this->account_number);
        if ($length > 4) {
            return str_repeat('*', $length - 4) . substr($this->account_number, -4);
        }
        return $this->account_number;
    }
}
