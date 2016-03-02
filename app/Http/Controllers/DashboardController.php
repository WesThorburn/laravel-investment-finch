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
        return view('pages/dashboard/market-cap-adjustments');
    }

    public function ajaxMarketCapAdjustments(){
        $stockCodesInMarketIndex = Stock::withMarketIndex('all')->lists('stock_code');
        $stocks = StockMetrics::join('stocks', 'stocks.stock_code', '=', 'stock_metrics.stock_code')
            ->select([
                'stock_metrics.stock_code', 
                'stocks.company_name',
                'stock_metrics.yesterdays_market_cap',
                'stock_metrics.current_market_cap',
                'stock_metrics.percent_change',
                \DB::raw('(stock_metrics.current_market_cap - stock_metrics.yesterdays_market_cap) AS difference'),
                'stock_metrics.market_cap_requires_adjustment',
            ])
            ->whereIn('stock_metrics.stock_code', $stockCodesInMarketIndex);
        return \Datatables::of($stocks)
            ->editColumn('yesterdays_market_cap', function($stock){
                if($stock->yesterdays_market_cap == 0.00){
                    return null;
                }
                elseif($stock->yesterdays_market_cap < 1000){
                    return number_format($stock->yesterdays_market_cap, 2, '.', '');
                }
                return number_format($stock->yesterdays_market_cap);
            })
            ->editColumn('current_market_cap', function($stock){
                if($stock->current_market_cap == 0.00){
                    return null;
                }
                elseif($stock->current_market_cap < 1000){
                    return number_format($stock->current_market_cap, 2, '.', '');
                }
                return number_format($stock->current_market_cap);
            })
            ->editColumn('difference', function($stock){
                return number_format($stock->difference, 2, '.', '');
            })
            ->editColumn('percent_change', function($stock){
                if($stock->percent_change > 0){
                    return "<div class='color-green'>".number_format($stock->percent_change, 2)."%"."</div>";
                }
                elseif($stock->percent_change < 0){
                    return "<div class='color-red'>".number_format($stock->percent_change, 2)."%"."</div>";
                }
                return number_format($stock->percent_change, 2).'%';
            })
            ->editColumn('market_cap_requires_adjustment', function($stock){
                if($stock->market_cap_requires_adjustment){
                    return "Yes";
                }
                else{
                    return "No";
                }
            })
            ->addColumn('change_adjustment', function($stock){
                if($stock->market_cap_requires_adjustment){
                    return '<a href="/dashboard/marketCapAdjustments/'.$stock->stock_code.'/0" class="btn btn-default btn-row glyphicon glyphicon-remove center-block"></a>';
                }
                else{
                    return '<a href="/dashboard/marketCapAdjustments/'.$stock->stock_code.'/1" class="btn btn-default btn-row glyphicon glyphicon-plus center-block"></a>';
                }
            })
            ->make(true);
    }

    public function changeStockAdjustmentStatus($stockCode, $addOrRemove){
        if($addOrRemove == 1 || $addOrRemove == 0){
            $stockMetric = StockMetrics::where('stock_code', $stockCode)->first();
            $stockMetric->market_cap_requires_adjustment = $addOrRemove;
            $stockMetric->save();
            return redirect('/dashboard/marketCapAdjustments');
        }
        return redirect('/');
    }
}
