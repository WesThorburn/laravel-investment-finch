<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SearchRequest;
use App\Http\Controllers\Controller;
use App\Models\Historicals;
use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Carbon\Carbon;
use Khill\Lavacharts\Lavacharts;

class StockController extends Controller
{
    public function index(SearchRequest $request){
        $stocks = StockMetrics::getMetricsByStockList(Stock::lists('stock_code'), "omit");
        return view('pages.stocks')->with([
            'stockSectors' => Stock::getSectorDropdown(),
            'stocks' => $stocks,
            'stockSectorName' => $request->stockSector
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
            $motRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate();

            return view('pages.individualstock')->with([
                'stockPriceLava' => $stockPriceLava,
                'stock' => Stock::where('stock_code', $id)->first(),
                'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit'),
                'metrics' => StockMetrics::where('stock_code', $id)->first(),
                'mostRecentStockHistoricals' => Historicals::where('stock_code', $id)->orderBy('date', 'DESC')->limit(1)->first(),
                'sectorAverage' => SectorHistoricals::where(['sector' => $sector, 'date' => $motRecentSectorHistoricalsDate])->first()
            ]);
        }
        return redirect('/');
    }

    public function getCurrentPrice($id){
        return "$".StockMetrics::where('stock_code', $id)->pluck('last_trade');
    }

    public function getDayChange($id){
        return view('layouts.partials.individual-stock-day-change')->with([
            'dayChange' => StockMetrics::where('stock_code', $id)->pluck('day_change')
        ]);
    }

    public function relatedStocks($id){
        return view('layouts.partials.related-stock-list-display')->with([
            'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit')
        ]);
    }

    public function stocks(){
        return StockMetrics::with('stock')->orderBy('market_cap', 'DESC')->paginate(20);
    }
}
