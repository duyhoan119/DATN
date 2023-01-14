<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefundExportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_shipment_id',
        'supplier_id',
        'refund_totall_quantity',
        'refund_type',
        'user_id',
        'refund_code',
        'description',
        'refund_price_totail',
        'status'
    ];

    public function refundExportShipmentDetail(): HasMany
    {
        return $this->hasMany(RefundExportShipmentDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
