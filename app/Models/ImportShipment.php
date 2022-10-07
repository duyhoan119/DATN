<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'status',
        'import_date',
        'import_price_totai',
    ];
}
