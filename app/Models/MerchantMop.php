<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantMop extends Model
{
    use HasFactory;

    protected $table = 'merchant_mop';

    protected $primaryKey = 'merchant_mop_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'merchant_id',
        'account_type',
        'cod_terms_accepted',
        'description',
        'account_name',
        'account_number',
        'gcash_qr_code',
        'gcash_number',
        'created_at',
    ];

    // Relationship to the Merchant model
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

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
