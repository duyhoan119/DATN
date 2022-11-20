<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportShipmentRepuest;
use App\Http\Requests\SearchImportShipment;
use App\Http\Resources\ImportShipmentDetailResource;
use App\Http\Resources\ImportShipmentResource;
use App\Models\ImportShipment;
use App\Models\ImportShipmentDetail;
use App\Models\Product;
use App\Models\ProductVersion;
use App\Models\Supplier;

class ImportShipmentController extends Controller
{
    public function index(SearchImportShipment $request)
    {
        $importShipments = ImportShipment::query()
            ->orderBy('created_at', 'DESC')->paginate(15);
        return new ImportShipmentResource($importShipments);
    }

    public function save(ImportShipmentRepuest $request)
    {
        $createImportShipmentData = $request->all();
        $products = collect($createImportShipmentData['products']);
        $createImportShipmentData['import_code'] = $this->GetImportCode();
        $createImportShipmentData['quantity'] = array_sum($products->pluck('quantity')->toArray());
        $createImportShipmentData['import_price_totail'] = array_sum($this->GetTotallPrice($products));
        if ($importShipment = ImportShipment::query()->create($createImportShipmentData)) {

            $importShipmentDetailDatas = $this->getImportShipmentDetailData($importShipment->id, $request->products);

            foreach ($importShipmentDetailDatas as $importShipmentDetailData) {

                $importShipmentDetail = ImportShipmentDetail::query()->create($importShipmentDetailData);

                $product = Product::find($importShipmentDetail->product_id);

                if ($product->import_price != $importShipmentDetail->import_price) {
                    $productVersionData = [
                        'name' => $product->name,
                        'import_price' => $product->import_price,
                        'price' => $product->price,
                        'product_id' => $product->id,
                        'sku' => $product->sku,
                        'category_id' => $product->category_id
                    ];

                    ProductVersion::query()->create($productVersionData);

                    $product->quantity += $importShipmentDetail->quantity;
                    $product->import_price = $importShipmentDetail->import_price;
                    $product->save();
                }
                $product->quantity += $importShipmentDetail->quantity;
                $product->save();
            }
        }

        return true;
    }

    protected function GetImportCode()
    {
        $latestImportShipment = ImportShipment::latest('id')->first(['id']);
        $latestId = $latestImportShipment->id ?? 0;

        return 'MNH' . str_pad(++$latestId, 7, '0', STR_PAD_LEFT);
    }

    protected function getImportShipmentDetailData($importShipmentId, $products)
    {
        $result = [];

        foreach ($products as $product) {
            $item['import_shipment_id'] = $importShipmentId;
            $item['product_id'] = $product['id'];
            $item['quantity'] = $product['quantity'];
            $item['import_price'] = $product['import_price'];
            $result[] = $item;
        }

        return $result;
    }

    protected function GetIdSupplierByKeyword($keyword)
    {
        return Supplier::query()->where('name', 'iLIKE', '%' . $keyword . '%');
    }

    public function getDetail($import_id)
    {
        $importShipmentDetail = ImportShipmentDetail::query()->where('import_shipment_id', $import_id)->with('product')->get();
        return new ImportShipmentDetailResource($importShipmentDetail);
    }

    protected function GetTotallPrice($products)
    {
        $result = [];

        foreach ($products as $product) {
            $result[] = $product['quantity'] * $product['import_price'];
        }

        return $result;
    }
}
