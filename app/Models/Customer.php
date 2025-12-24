<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $with = ['wallet'];

    public function wallet(){
        return $this->hasOne(Wallet::class, 'customer_id', 'id');
    }
    public function virtualAccount(){
        return $this->hasOneThrough(VirtualAccount::class, Wallet::class, 'customer_id', 'wallet_id', 'id', 'id');
    }
    public function review(){
        return $this->hasMany(Review::class, 'customer_id', 'id');
    }
    public function likeProducts(){
        return $this->belongsToMany(Product::class, 'table_customers_likes_products', 'customer_id', 'product_id')
        ->withPivot('created_at')
        ->using(Like::class);
    }
    public function likeProductsLastWeek(){
        return $this->belongsToMany(Product::class, 'table_customers_likes_products', 'customer_id', 'product_id')
        ->withPivot('created_at')
        ->wherePivot('created_at', '>=', now()->subWeek())
        ->using(Like::class);
    }
    public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
}
