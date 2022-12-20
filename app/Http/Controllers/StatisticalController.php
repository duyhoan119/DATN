<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatisticalResource;
use App\Models\ExportShipment;
use App\Models\ExportShipmentDetail;
use App\Models\ImportShipment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function show()
    {
        $now = Carbon::now();
        $salesMoneyInMonth = ExportShipment::query()->whereMonth('created_at', $now->month)->sum('totall_price');
        $importMoneyInMonth = ImportShipment::query()->whereMonth('created_at', $now->month)->sum('import_price_totail');
        $interestInMonth = $salesMoneyInMonth - $importMoneyInMonth;

        $funds = ImportShipment::sum('import_price_totail');

        $bestSellingProducts = ExportShipmentDetail::select(
            'product_id',
            DB::raw('SUM(export_shipment_details.quantity) as total_quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->with('product')
            ->get();

        $mostProfitableProducts = ExportShipmentDetail::select(
            'product_id',
            DB::raw('SUM(export_shipment_details.price * export_shipment_details.quantity) as total_price,COUNT(export_shipment_details.export_shipment_id) as totail_order')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('product_id')
            ->orderBy('total_price', 'desc')
            ->limit(5)
            ->with('product')
            ->get();

        $productTotail = Product::query()->sum('quantity');

        $result['interestInMonth'] = $interestInMonth;
        $result['funds'] = $funds;
        $result['bestSellingProducts'] = $bestSellingProducts;
        $result['mostProfitableProducts'] = $mostProfitableProducts;
        $result['productTotail'] = $productTotail;

        return new StatisticalResource($result);
    }
}
