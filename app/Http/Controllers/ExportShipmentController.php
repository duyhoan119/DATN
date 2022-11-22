<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportShipmentRequest;
use App\Http\Requests\SearchExportShipment;
use App\Http\Resources\ExportShipmentDetailResource;
use App\Http\Resources\ExportShipmentResource;
use App\Models\ExportShipment;
use App\Models\ExportShipmentDetail;
use App\Models\Product;
use Carbon\Carbon;

class ExportShipmentController extends Controller
{
    public function index(SearchExportShipment $request)
    {
        $importShipments = ExportShipment::query()
            ->with('user')
            ->orderBy('created_at', 'DESC')->paginate(15);
        return new ExportShipmentResource($importShipments);
    }

    public function save(ExportShipmentRequest $request)
    {
        $createExportShipmentData = $request->all();
        $products = collect($createExportShipmentData['products']);
        $createExportShipmentData['export_code'] = $this->GetExportCode();
        $createExportShipmentData['quantity'] = array_sum($products->pluck('quantity')->toArray());
        $createExportShipmentData['totall_price'] = array_sum($this->GetTotallPrice($products));
        $createExportShipmentData['export_date'] = Carbon::createFromFormat('d/m/Y', $createExportShipmentData['export_date'])->format('Y-m-d H:i:s');
        if ($exportShipment = ExportShipment::query()->create($createExportShipmentData)) {

            $exportShipmentDetailDatas = $this->getExportShipmentDetailData($exportShipment->id, $request->products);

            foreach ($exportShipmentDetailDatas as $exportShipmentDetailData) {

                $exportShipmentDetail = ExportShipmentDetail::query()->create($exportShipmentDetailData);

                $product = Product::find($exportShipmentDetail->product_id);

                $product->quantity -= $exportShipmentDetail->quantity;
                $product->save();
            }
        }

        return true;
    }

    protected function GetTotallPrice($products)
    {
        $result = [];

        foreach ($products as $product) {
            $result[] = $product['quantity'] * $product['price'];
        }

        return $result;
    }

    protected function GetExportCode()
    {
        $latestExportShipment = ExportShipment::latest('id')->first(['id']);
        $latestId = $latestExportShipment->id ?? 0;

        return 'MXH' . str_pad(++$latestId, 7, '0', STR_PAD_LEFT);
    }

    protected function getExportShipmentDetailData($exportShipmentId, $products)
    {
        $result = [];

        foreach ($products as $product) {
            $item['export_shipment_id'] = $exportShipmentId;
            $item['product_id'] = $product['id'];
            $item['quantity'] = $product['quantity'];
            $item['price'] = $product['price'];
            $result[] = $item;
        }

        return $result;
    }

    public function getDetail($export_id)
    {
        $importShipmentDetail = ExportShipmentDetail::query()->where('export_shipment_id', $export_id)->with('product')->get();
        return new ExportShipmentDetailResource($importShipmentDetail);
    }
}
