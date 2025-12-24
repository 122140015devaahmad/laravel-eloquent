<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    protected static function booted(){
        parent::booted();
        static::addGlobalScope(new IsActiveScope());
    }
    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
    public function cheapestProduct(){
        return $this->hasOne(Product::class, 'category_id', 'id')->oldest('price');
    }
    public function mostExpensiveProduct(){
        return $this->hasOne(Product::class, 'category_id', 'id')->latest('price');
    }
    public function review(){
        return $this->hasManyThrough(Review::class, Product::class, 'category_id', 'product_id', 'id', 'id');
    }
}
