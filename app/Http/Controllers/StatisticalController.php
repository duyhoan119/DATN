<?php

namespace App\Http\Controllers;

use App\Models\ExportShipment;
use App\Models\ExportShipmentDetail;
use App\Models\ImportShipment;
use App\Models\ImportShipmentDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function show(Request $request)
    {
        $now = Carbon::now();
        $yesterday = Carbon::yesterday();
        $DayOfWeek = $now->weekOfMonth;

        $salesMoneyInMonth = ExportShipment::query()->whereMonth('created_at', $now->month)->orderBy('created_at')->get();
        $salesMoneyInYesterday = ExportShipment::query()->whereDay('created_at', $yesterday->day)->sum('totall_price');
        $salesMoneyInNow = ExportShipment::query()->whereDay('created_at', $now->day)->sum('totall_price');
        $salesMoneyInDayOfWeek = ExportShipment::query()->whereMonth('export_date', $DayOfWeek)->sum('totall_price');

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
            DB::raw('SUM(export_shipment_details.price * export_shipment_details.quantity) as total_price,COUNT(export_shipment_details.export_shipment_id) as totail_order,SUM(export_shipment_details.quantity) as quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('product_id')
            ->orderBy('total_price', 'desc')
            ->limit(5)
            ->with('product')
            ->get();

        $productTotail = Product::query()->sum('quantity');
        $salesInMonth = '';
        if (!empty($request->month)) {
            $month = $request->month; 
            $salesInMonth = ExportShipment::query()->whereMonth('created_at', $month)->orderBy('created_at')->groupBy('created_at')
            ->get();
        } 
        

        $result = [
            'sales_money_in_month' => $salesMoneyInMonth,
            'sales_in_month' => $salesInMonth,
            'sales_money_in_yesterday' => $salesMoneyInYesterday,
            'sales_money_in_now' => $salesMoneyInNow,
            'sales_money_in_day_ofWeek' => $salesMoneyInDayOfWeek,
            'funds' => $funds,
            'product_totail' => $productTotail,
            'best_selling_products' => $bestSellingProducts,
            'most_profitable_products' => $mostProfitableProducts,
        ];

        
        return json_encode($result);
    }

    public function supplier(Request $request)
    {
        $supplierAll = ExportShipment::select(
            'supplier_id',
            DB::raw('SUM(export_shipments.totall_price) as total_price,SUM(export_shipments.quantity) as quantity')
        )
            ->leftJoin('suppliers', 'suppliers.id', '=', 'export_shipments.supplier_id')
            ->groupBy('supplier_id')
            ->limit(5)
            ->with('supplier')
            ->get();
        $import_price_totail = ImportShipment::select(
            'supplier_id',
            DB::raw('SUM(import_shipments.import_price_totail) as import_price_totail,SUM(import_shipments.quantity) as quantity')
        )
            ->leftJoin('suppliers', 'suppliers.id', '=', 'import_shipments.supplier_id')
            ->groupBy('supplier_id')
            ->limit(5)
            ->with('supplier')
            ->get();

        if (!empty($request->all())) {
            $data = $request->all();
            $from_date = $data['from_date'];
            $to_date = $data['to_date'];
            $supplier_id = $data['supplier_id'];

            $supplier = ExportShipment::whereBetween('export_date', [$from_date, $to_date])->where('supplier_id', '=', $supplier_id)->orderBy('export_date', 'ASC')->get();

            return json_encode($supplier);
        }

        $result = [
            'supplier_all' => $supplierAll,
            'import_price_totail' => $import_price_totail,
        ];
        return json_encode($result);
    }

    public function product(Request $request)
    {
        $ProductExportAll = ExportShipmentDetail::select('lot_code')->select(
            'product_id',
            DB::raw('SUM(export_shipment_details.price * export_shipment_details.quantity) as total_price,COUNT(export_shipment_details.export_shipment_id) as totail_order,SUM(export_shipment_details.quantity) as quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('lot_code', 'product_id')
            ->orderBy('total_price', 'desc')
            ->limit(5)
            ->with('product')
            ->get();

        if (!empty($request->all())) {

            $data = $request->all();
            $from_date = $data['from_date'];
            $lot_code = $data['lot_code'];
            $to_date = $data['to_date'];
            $product_id = $data['product_id'];

            $ProductDetail = ProductDetail::where('product_id', '=', $product_id)->where('lot_code', '=', $lot_code)->get();
            $ProductFiler = ExportShipmentDetail::whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->where('lot_code', '=', $lot_code)->orderBy('created_at', 'ASC')->get();
            $totalProduct = ExportShipmentDetail::whereBetween('created_at', [$from_date, $to_date])->where('lot_code', '=', $lot_code)->where('product_id', '=', $product_id)->sum('quantity');

            foreach ($ProductFiler as $key => $iteam) {
                $interest = $iteam->price * $iteam->quantity;
                $interest += $interest;
            }
            $profit = $interest - $ProductDetail[0]['import_price'] * $totalProduct;
            $result = ['profit' => $profit, 'totalProduct' => $totalProduct, 'import_price' => $ProductDetail[0]['import_price'], 'product_filer' => $ProductFiler];
            return json_encode($result);
        }
        $result = $ProductExportAll;
        return json_encode($result);
    }

    public function inventoryProduct(Request $request)
    {
        $ProductExportQuantity = ExportShipmentDetail::select(
            'product_id',
            DB::raw('SUM(export_shipment_details.quantity) as quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('product_id')
            ->limit(5)
            ->with('product')
            ->get();
        $import_quantity_totail = ImportShipmentDetail::select(
            'product_id',
            DB::raw(' SUM(import_shipment_detail.quantity) as quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'import_shipment_detail.product_id')
            ->groupBy('product_id')
            ->limit(5)
            ->with('product')
            ->get();

        if (!empty($request->all())) {

            $data = $request->all();
            $from_date = $data['from_date'];
            $to_date = $data['to_date'];

            if(!empty($data['product_id']) ){
                $product_id = $data['product_id'];

            $Product = Product::where('status', '=', 1)->find($product_id);
            $quantity_import = ImportShipmentDetail::query()->whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->orderBy('created_at', 'ASC')->sum('quantity');
            $quantity_export = ExportShipmentDetail::query()->whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->orderBy('created_at', 'ASC')->sum('quantity');
            $result = ['Product' => $Product, 'quantity_import' => $quantity_import, 'quantity_export' => $quantity_export];
            }else{
                $ProductExportQuantity_filer_Date = ExportShipmentDetail::query()->whereBetween('export_shipment_details.created_at', [$from_date, $to_date])->select(
                    'product_id',
                    DB::raw('SUM(export_shipment_details.quantity) as quantity')
                )
                    ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
                    ->groupBy('product_id')
                    ->limit(5)
                    ->with('product')
                    ->get();
                $import_quantity_totail_filer_Date = ImportShipmentDetail::query()->whereBetween('import_shipment_detail.created_at', [$from_date, $to_date])->select(
                    'product_id',
                    DB::raw(' SUM(import_shipment_detail.quantity) as quantity')
                )
                    ->leftJoin('products', 'products.id', '=', 'import_shipment_detail.product_id')
                    ->groupBy('product_id')
                    ->limit(5)
                    ->with('product')
                    ->get();
            $result = ['quantity_import' => $ProductExportQuantity_filer_Date, 'quantity_export' => $import_quantity_totail_filer_Date];
            }
            // tồn đầu kỳ = tồn thời gian hiện tại + số lượng xuất trong thời gian filer đầu tới hiện tại - số lượng nhập từ ngày hiện tại trở về ngày filer đầu
            // tồn cuối kỳ = tồn đầu kỳ + số lượng nhập trong kỳ - đi số lượng xuất trong kỳ
            // 1000 = 2000 + 1000 - 1500 

            
            return json_encode($result);
        }
        $result = ['product_export_quantity' => $ProductExportQuantity, 'import_quantity_totail' => $import_quantity_totail];
        return json_encode($result);
    }

    public function inventorySupplier(Request $request)
    {
        $supplier_quantity_export = ExportShipment::select(
            'supplier_id',
            DB::raw('SUM(export_shipments.quantity) as quantity')
        )
            ->leftJoin('suppliers', 'suppliers.id', '=', 'export_shipments.supplier_id')
            ->groupBy('supplier_id')
            ->limit(5)
            ->with('supplier')
            ->get();
            
        $supplier_quantity_import = ImportShipment::select(
            'supplier_id',
            DB::raw('SUM(import_shipments.quantity) as quantity')
        )
            ->leftJoin('suppliers', 'suppliers.id', '=', 'import_shipments.supplier_id')
            ->groupBy('supplier_id')
            ->limit(5)
            ->with('supplier')
            ->get();

        if (!empty($request->all())) {
            $data = $request->all();
            $from_date = $data['from_date'];
            $to_date = $data['to_date'];
            $supplier_id = $data['supplier_id'];

            $supplier_quantity_export = ExportShipment::whereBetween('export_date', [$from_date, $to_date])->where('supplier_id', '=', $supplier_id)->orderBy('export_date', 'ASC')->get();
            $supplier_quantity_import = ImportShipment::whereBetween('import_date', [$from_date, $to_date])->where('supplier_id', '=', $supplier_id)->orderBy('import_date', 'ASC')->get();

            $result = [ 
                'supplier_export' => [ 'supplier_quantity_export' => $supplier_quantity_export->sum('quantity'),
                'supplier_export' => $supplier_quantity_export
            ],
                'supplier_import' => [ 'supplier_quantity_import' => $supplier_quantity_import->sum('quantity'),
                'supplier_import' => $supplier_quantity_import
            ],
            ];
            return json_encode($result);
        }

        $result = [ 
            'supplier_quantity_export' => $supplier_quantity_export,
            'supplier_quantity_import' => $supplier_quantity_import,
        ];
        return json_encode($result);
    }
}
