<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $hidden = [
        'category_id'
    ];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function review(){
        return $this->hasMany(Review::class, 'product_id', 'id');
    }
    public function likedByCustomer(){
        return $this->belongsToMany(Customer::class, 'table_customers_likes_products', 'product_id', 'customer_id')
        ->withPivot('created_at')
        ->using(Like::class);
    }
    public function image(){
        return $this->morphOne(Image::class, 'imageable');
    }
    public function comment(){
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function latestComment(){
        return $this->morphOne(Comment::class, 'commentable')->latest('created_at');
    }
    public function oldestComment(){
        return $this->morphOne(Comment::class, 'commentable')->oldest('created_at');
    }
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
