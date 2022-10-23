<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportShipmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_shipment_id',
        'product_id',
        'quantity',
        'price',
    ];
}
