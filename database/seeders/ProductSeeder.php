<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->id = 'GADGET-1';
        $product->name = 'Gadget 1';
        $product->description = 'Gadget 1 Description';
        $product->category_id = 'GADGET';
        $product->save();

        $product2 = new Product();
        $product2->id = 'GADGET-2';
        $product2->name = 'Gadget 2';
        $product2->description = 'Gadget 2 Description';
        $product2->category_id = 'GADGET';
        $product2->price = 1000;
        $product2->save();
    }
}
