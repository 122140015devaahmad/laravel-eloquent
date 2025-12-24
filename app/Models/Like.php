<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    protected $table = 'table_customers_likes_products';
    protected $foreignKey = 'customer_id';
    protected $relatedKey = 'product_id';
    public $timestamps = false;

    public function usesTimestamps(){
        return false;
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
