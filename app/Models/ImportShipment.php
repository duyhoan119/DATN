<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'product_id',
        'import_date',
        'quantity',
        'import_price_totail',
    ];
}
