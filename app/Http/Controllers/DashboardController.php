<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\StockMetrics;
use App\Models\Historicals;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function discontinued(){
        return view('pages/dashboard/discontinued')->with([
        	'discontinuedStocks' => Stock::onlyTrashed()->get()
        ]);
    }

    public function marketCapAdjustmentsPage(){
        return view('pages/dashboard/market-cap-adjustments')->with([
            'marketCapAdjustments' => StockMetrics::where('market_cap_requires_adjustment', 1)->get()
        ]);
    }

    public function ajaxMarketCapAdjustments(){
        return "Ajax Route";
    }

    public function changeStockAdjustmentStatus(Request $request){
        $stockMetric = StockMetrics::where('stock_code', $request->stockCode)->first();
        $stockMetric->market_cap_requires_adjustment = $request->adjustment;
        $stockMetric->save();
        return redirect('/dashboard/marketCapAdjustments');
    }
}
