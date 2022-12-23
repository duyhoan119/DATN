<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatisticalResource;
use App\Http\Resources\StatisticalProductResource;
use App\Models\ExportShipment;
use App\Models\ExportShipmentDetail;
use App\Models\ImportShipment;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function show()
    {
        $now = Carbon::now();
        $yesterday = Carbon::yesterday();
        $DayOfWeek = $now->weekOfMonth;
        
        $salesMoneyInMonth = ExportShipment::query()->whereMonth('created_at', $now->month)->get(); 
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
        $result = [
            'sales_money_in_month' => $salesMoneyInMonth,
            'sales_money_in_yesterday' => $salesMoneyInYesterday ,
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
            
        if (!empty($request->all())) {
            $data = $request->all();
            $from_date = $data['from_date'];
            $to_date = $data['to_date'];
            $supplier_id = $data['supplier_id'];

            $supplier = ExportShipment::whereBetween('export_date',[$from_date,$to_date])->where('supplier_id', '=', $supplier_id)->orderBy('export_date','ASC')->get();

            return json_encode($supplier);
        }

        return json_encode($supplierAll);
    }

    public function product(Request $request)
    { 
        $ProductSoldAll = ExportShipmentDetail::select('lot_code')->select(
            'product_id',
            DB::raw('SUM(export_shipment_details.price * export_shipment_details.quantity) as total_price,COUNT(export_shipment_details.export_shipment_id) as totail_order,SUM(export_shipment_details.quantity) as quantity')
        )
            ->leftJoin('products', 'products.id', '=', 'export_shipment_details.product_id')
            ->groupBy('lot_code','product_id')
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

            $ProductDetail = ProductDetail::where('product_id', '=', $product_id)->where('lot_code', '=', $lot_code )->get();
            
            $ProductFiler = ExportShipmentDetail::whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->where('lot_code', '=', $lot_code)->orderBy('created_at', 'ASC')->get();
            $totalProduct = ExportShipmentDetail::whereBetween('created_at', [$from_date, $to_date])->where('lot_code', '=', $lot_code )->where('product_id', '=', $product_id)->sum('quantity');
            foreach ($ProductFiler as $key => $iteam) {
                $interest = $iteam->price * $iteam->quantity;
                $interest += $interest;
            } 
            $profit = $interest - $ProductDetail[0]['import_price'] * $totalProduct; 
            $result = ['profit' => $profit, 'totalProduct' => $totalProduct , 'import_price' => $ProductDetail[0]['import_price'], 'product_filer' => $ProductFiler ]; 
            return json_encode($result);
        } 
        $result['product_sold_all'] = $ProductSoldAll;
        return json_encode($result);
    }

    public function inventoryProduct(Request $request)
    { 
        $ProductSoldAll = ProductDetail::where('status', '=', 1)->limit(5) 
            ->get();

        if (!empty($request->all())) {

            $data = $request->all();
            $from_date = $data['from_date'];
            $lot_code = $data['lot_code'];
            $to_date = $data['to_date'];
            $product_id = $data['product_id']; 

            $Product = Product::where('status', '=', 1)->find($product_id);
            
            $ProductFiler = ExportShipmentDetail::whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->where('lot_code', '=', $lot_code)->orderBy('created_at', 'ASC')->get();
            $totalProduct = ExportShipmentDetail::query()->whereBetween('created_at', [$from_date, $to_date])->where('product_id', '=', $product_id)->sum('quantity');

            foreach ($ProductFiler as $key => $iteam) {
                $interest = $iteam->price * $iteam->quantity;
                $interest += $interest;
            }
            $interest = $interest - $Product->import_price * $totalProduct; 
            $result['product_filer'] = ['interest' => $interest, 'product_Z' => $ProductFiler ]; 
            return json_encode($result);
        } 
        $result['product_sold_all'] = $ProductSoldAll;
        return json_encode($result);
    }
} 