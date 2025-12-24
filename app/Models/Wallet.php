<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = true;
    public $timestamps = false;

    public function wallet(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function virtualAccount(){
        return $this->hasOne(VirtualAccount::class, 'wallet_id', 'id');
    }
}
