<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function products(){
        return $this->morphedByMany(Product::class, 'taggable');    
    }
    public function vouchers(){
        return $this->morphedByMany(Voucher::class, 'taggable');    
    }
}
