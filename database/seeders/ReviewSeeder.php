<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $review = new Review();
        $review->product_id = 'GADGET-1';
        $review->customer_id = 'EARL';
        $review->rating = 5;
        $review->comment = 'KEREN BANGET GILA';
        $review->save();

        $review = new Review();
        $review->product_id = 'GADGET-2';
        $review->customer_id = 'EARL';
        $review->rating = 4;
        $review->comment = 'MANTEP COK';
        $review->save();
    }
}
