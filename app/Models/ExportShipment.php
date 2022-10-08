<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'receive_phone',
        'export_date',
        'totail_price',
    ];
}
