<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    protected $table = 'virtual_accounts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    public function virtualAccount(){
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
