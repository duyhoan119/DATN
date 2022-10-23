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
<<<<<<< HEAD
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
}
=======
        'sku',
        'category_id',
        'impost_price',
        'price',
        'quantity',
        'description',
        'status',
        'warranty_date',
    ];
}
>>>>>>> develop
