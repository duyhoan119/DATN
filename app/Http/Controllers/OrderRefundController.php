<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRefundRequest;
use App\Models\ExportShipment;
use Illuminate\Database\Eloquent\Builder;

class OrderRefundController extends Controller
{

    public function index(OrderRefundRequest $request)
    {
    }
    public function show(OrderRefundRequest $request)
    {
    }
    public function store(OrderRefundRequest $request)
    {
    }

    public function SearchExportShipment(OrderRefundRequest $request)
    {
        $exportShipment = ExportShipment::query()
            ->when(isset($request['export_code']), function (Builder $query, $request) {
                return $query->where('export_code', $request['export_code']);
            })
            ->when(isset($request['lot_code']), function (Builder $query, $request) {
                return $query->whereRelation('lot_code', $request['lot_code']);
            })->with('exportShipmentDetails');
    }
}
