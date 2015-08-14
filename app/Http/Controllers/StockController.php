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
            $stockPriceLava = new Lavacharts;
            $prices = \Lava::DataTable();
            $prices->addStringColumn('Date')
                ->addNumberColumn('Price')
                ->addRows($priceGraphData);

            $stockPriceLava = \Lava::AreaChart('StockPrice')
                ->dataTable($prices)
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

    public function graph($stockCode, $timeFrame, $dataType){
        $graphData = Stock::getGraphData($stockCode, $timeFrame, $dataType);
        $prices = \Lava::DataTable();
        $prices->addStringColumn('Date')
            ->addNumberColumn($dataType)
            ->addRows($graphData);
        return $prices->toJson();
    }

    public function relatedStocks($id){
        return view('layouts.partials.related-stock-list-display')->with([
            'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit')
        ]);
    }
}
