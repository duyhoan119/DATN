<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportShipmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'import_shipment_id',
        'quantity',
        'import_price',
    ];
}
