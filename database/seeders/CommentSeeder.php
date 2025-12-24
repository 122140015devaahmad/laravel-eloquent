<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCommentsForProduct();
        $this->createCommentsForVoucher();
    }
    public function createCommentsForProduct(){
        $product = Product::query()->first();
        $comment = new Comment();
        $comment->email = 'Tb9rE@example.com';
        $comment->title = 'Test Title';
        $comment->comment = 'Test Comment';
        $comment->commentable_id = $product->id;
        $comment->commentable_type = Product::class;
        $comment->save();
    }
    public function createCommentsForVoucher(){
        $voucher = Voucher::query()->first();
        $comment2 = new Comment();
        $comment2->email = 'AsWlu@example.com';
        $comment2->title = 'Test Judul';
        $comment2->comment = 'Test Comment 2';
        $comment2->commentable_id = $voucher->id;
        $comment2->commentable_type = Voucher::class;
        $comment2->save();
    }
}
