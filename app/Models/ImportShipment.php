<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'import_date',
        'quantity',
        'import_code',
        'import_type',
        'payment',
        'import_price_totail',
        'description'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
