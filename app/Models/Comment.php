<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true; //Default True

    protected $attributes = [
        'title' => 'Sample title',
        'comment' => 'Sample comment'
    ];
    public function commentable(){
        return $this->morphTo();
    }
}
