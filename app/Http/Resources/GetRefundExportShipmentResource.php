<?php

namespace App\Http\Resources;

use App\Models\RefundExportShipment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRefundExportShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->resource->map(fn (RefundExportShipment $refundExportShipment) => [
            'id' => $refundExportShipment->id,
            'user_id' => $refundExportShipment->user_id,
            'user_name' => $refundExportShipment->user->name,
            'seller_name' => $refundExportShipment->supplier->name,
            'refund_code' => $refundExportShipment->refund_code,
            'refund_price_totail' => $refundExportShipment->refund_price_totail,
            'refund_totall_quantity' => $refundExportShipment->refund_totall_quantity,
            'status' => $refundExportShipment->status,
            'description' =>$refundExportShipment->description,
            'refund_type' => $refundExportShipment->refund_type,
            'created_at' => $refundExportShipment->created_at ? Carbon::createFromFormat('Y-m-d H:i:s', $refundExportShipment->created_at)->format('d/m/Y') : '',
            'updated_at' => $refundExportShipment->updated_at ? Carbon::createFromFormat('Y-m-d H:i:s', $refundExportShipment->updated_at)->format('d/m/Y') : ''
        ]);
    }
}
