<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'category_id',
        'sku',
        'import_price',
        'price',
        'quantity',
        'description',
        'iamge',
        'status',
        'warranty_date',
    ];
    public function Category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    } 
}  
