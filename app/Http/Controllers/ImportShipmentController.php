<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportShipmentRepuest;
use App\Models\ImportShipment;
use App\Models\ImportShipmentDetail;
use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Http\JsonResponse;

class ImportShipmentController extends Controller
{
    public function save(ImportShipmentRepuest $request)
    {

        if ($importShipment = ImportShipment::query()->create($request->all())) {

            $importShipmentDetailData = [
                'import_shipment_id' => $importShipment->id,
                'quantity' => $request->quantity,
                'import_price' => $request->import_price
            ];
            $importShipmentDetail = ImportShipmentDetail::query()->create($importShipmentDetailData);
            $product = Product::find($importShipment->product_id);

            if ($product->import_price !== $importShipmentDetail->import_price) {
                $product->quantity += $request->quantity;
                $product->save();
            }

            $product->quantity += $request->quantity;
            $product->import_price = $request->import_price;

            $productVersionData = [
                'name' => $product->name,
                'import_price' => $product->import_price,
                'price' => $product->price,
                'product_id' => $product->id,
                'sku' => $product->sku,
                'category_id' => $product->category_id
            ];

            ProductVersion::query()->create($productVersionData);
            $product->save();
        }
    }
}
