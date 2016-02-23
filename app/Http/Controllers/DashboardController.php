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

    public function marketCapAdjustments(){
        $marketCapAdjustments = StockMetrics::whereNotIn('stock_code', Stock::onlyTrashed()->lists('stock_code'))->where('market_cap_requires_adjustment', 1)->get();
        $yesterdaysHistoricalDate = Historicals::getYesterdaysHistoricalsDate();

        foreach($marketCapAdjustments as $stock){
            $stock->yesterdays_market_cap = Historicals::where(['stock_code' => $stock->stock_code, 'date' => $yesterdaysHistoricalDate])->pluck('current_market_cap');
        }

        return view('pages/dashboard/market-cap-adjustments')->with([
            'marketCapAdjustments' => $marketCapAdjustments
        ]);
    }

    public function changeStockAdjustmentStatus(Request $request){
        $stockMetric = StockMetrics::where('stock_code', $request->stockCode)->first();
        $stockMetric->market_cap_requires_adjustment = $request->adjustment;
        $stockMetric->save();
        return redirect('/dashboard/marketCapAdjustments');
    }
}
