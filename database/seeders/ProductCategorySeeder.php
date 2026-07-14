<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name'=>'Productivity'],
            ['category_name'=>'Marketing'],
            ['category_name'=>'Code'],
            ['category_name'=>'Design'],
            ['category_name'=>'AI Tools'],
            ['category_name'=>'Ebook'],
            ['category_name'=>'Template'],
        ];
         foreach($categories as $category){
            ProductCategory::create($category);
        }
    }
}
