<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory as FactoriesHasFactory;
use Illuminate\Database\Factories\Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use FactoriesHasFactory;

    protected $fillable = [
        'product_name',
        'detail_product',
        'seller_id',
        'category_id',
        'price',
        'file_size',
        'file_url',
        'stock'
    ];
        public function category()
    {
        return $this->belongsTo(ProductCategory::class,'category_id','id');
    }

        public function seller()
    {
        return $this->belongsTo(User::class,'seller_id','id');
    }

}
