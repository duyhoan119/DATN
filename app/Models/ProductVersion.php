<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'product_id',
        'impost_price',
        'price',
        'sku',
    ];
}
