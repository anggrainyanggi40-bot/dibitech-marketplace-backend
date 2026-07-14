<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products=[
            [
              'product_name' =>'Belajar SQL',
              'detail_product' =>'Ebook SQL dasar',
              'seller_id'=>1,
              'category_id'=>'6',
              'price'=>50000,
              'file_size'=>5,
              'file_url'=>'sql.pdf',
              'stock'=>'5',
            ],
            [
              'product_name' =>'Notion Planner',
              'detail_product' =>'Daily productivity planner',
              'seller_id'=>1,
              'category_id'=>1,
              'price'=>45000,
              'file_size'=>100,
              'file_url'=>'planner.zip',
              'stock'=>'10',
            ],
            [
              'product_name' =>'Instagram Content Pack',
              'detail_product' =>'Marketing content templates',
              'seller_id'=>3,
              'category_id'=>2,
              'price'=>85000,
              'file_size'=>25,
              'file_url'=>'marketing.ai',
              'stock'=>3,
            ],
            [
              'product_name' =>'React Source Code',
              'detail_product' =>'Fullstack React project',
              'seller_id'=>3,
              'category_id'=>3,
              'price'=>150000,
              'file_size'=>500,
              'file_url'=>'react.zip',
              'stock'=>4,
            ],
            [
              'product_name' =>'Dashboard UI',
              'detail_product' =>'Modern UI Kit',
              'seller_id'=>5,
              'category_id'=>4,
              'price'=>75000,
              'file_size'=>20,
              'file_url'=>'dashboard.fig',
              'stock'=>15,
            ],

        ];
         foreach($products as $product){
            Product::create($product);
        }
    }
}
