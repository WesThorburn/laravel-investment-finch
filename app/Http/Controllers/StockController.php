<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SearchRequest;
use App\Http\Controllers\Controller;
use App\Models\Historicals;
use App\Models\SectorIndexHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Carbon\Carbon;
use Khill\Lavacharts\Lavacharts;
use Yajra\Datatables\Datatables;

class StockController extends Controller
{
    public function index($marketIndex){
        return view('pages.stocks')->with([
            'marketIndex' => $marketIndex,
            'formattedMarketIndex' => Stock::formatMarketIndex($marketIndex)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id){
        if($request->stockCodeFind){
            return redirect('stocks/'.$request->stockCodeFind);
        }
        if(Stock::where('stock_code', $id)->first()){
            $priceGraphData = Stock::getGraphData($id, 'last_month', 'Price');
            $prices = \Lava::DataTable();
            $prices->addStringColumn('Date')
                ->addNumberColumn('Price')
                ->addNumberColumn('50 Day Moving Average')
                ->addNumberColumn('200 Day Moving Average')
                ->addRows($priceGraphData);

            $stockPriceLava = \Lava::LineChart('StockPrice')
                ->dataTable($prices)
                ->customize([
                    'explorer' => [
                        'actions' => [
                            'dragToZoom',
                            'rightClickToReset'
                         ]
                     ]
                ])
                ->setOptions([
                    'width' => 620,
                    'height' => 360,
                    'title' => 'Price of '.strtoupper($id)
                ]);

            $sector = Stock::where('stock_code', $id)->pluck('sector');
            $motRecentSectorIndexHistoricalsDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();

            return view('pages.individualstock')->with([
                'stockPriceLava' => $stockPriceLava,
                'stock' => Stock::where('stock_code', $id)->first(),
                'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit'),
                'metrics' => StockMetrics::where('stock_code', $id)->first(),
                'mostRecentStockHistoricals' => Historicals::where('stock_code', $id)->orderBy('date', 'DESC')->limit(1)->first(),
                'sectorAverage' => SectorIndexHistoricals::where(['sector' => $sector, 'date' => $motRecentSectorIndexHistoricalsDate])->first()
            ]);
        }
        return redirect('/');
    }

    public function getCurrentPrice($id){
        return "$".StockMetrics::where('stock_code', $id)->pluck('last_trade');
    }

    public function getDayChange($id){
        return view('layouts.partials.individual-stock-day-change')->with([
            'dayChange' => StockMetrics::where('stock_code', $id)->pluck('percent_change')
        ]);
    }

    public function relatedStocks($id){
        return view('layouts.partials.related-stock-list-display')->with([
            'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit')
        ]);
    }

    public function stocks($marketIndex){
        $stockCodesInMarketIndex = Stock::withMarketIndex($marketIndex)->lists('stock_code');
        $stocks = StockMetrics::join('stocks', 'stocks.stock_code', '=', 'stock_metrics.stock_code')
            ->select([
                'stock_metrics.stock_code', 
                'stocks.company_name', 
                'stocks.sector', 
                'stock_metrics.last_trade',
                'stock_metrics.percent_change',
                'stock_metrics.market_cap',
                'stock_metrics.volume',
                'stock_metrics.EBITDA',
                'stock_metrics.earnings_per_share_current',
                'stock_metrics.price_to_earnings',
                'stock_metrics.price_to_book',
                'stock_metrics.year_high',
                'stock_metrics.year_low'
            ])
            ->whereIn('stock_metrics.stock_code', $stockCodesInMarketIndex);
        return \Datatables::of($stocks)
            ->editColumn('percent_change', function($stock){
                if($stock->percent_change > 0){
                    return "<div class='color-green'>".$stock->percent_change."%"."</div>";
                }
                elseif($stock->percent_change < 0){
                    return "<div class='color-red'>".$stock->percent_change."%"."</div>";
                }
                return $stock->percent_change.'%';
            })
            ->make(true);
    }
}
