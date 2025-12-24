<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    // use HasUuids;
    // protected $table = 'vouchers';
    // protected $primaryKey = 'id';
    // protected $keyType = 'uuid';
    // public $incrementing = false;
    // public $timestamps = false;

    use HasUuids, SoftDeletes;

    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $keyType = 'uuid';
    public $incrementing = false;
    public $timestamps = false;

    public function uniqueIds(): array
    {
        return ['id', 'voucher_code'];
    }

    public function scopeActive(Builder $builder){
        $builder->where('is_active', true);
    }
    public function scopeNonActive(Builder $builder){
        $builder->where('is_active', false);
    }
    public function comment(){
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
