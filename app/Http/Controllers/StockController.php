<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Repositories\IndividualStockRepositoryInterface;
use Carbon\Carbon;
use Khill\Lavacharts\Lavacharts;

class StockController extends Controller
{
    public function __construct(IndividualStockRepositoryInterface $stock){
        $this->stock = $stock;
    }

    public function index(){
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        if(Stock::where('stock_code', $id)->first()){
            $priceGraphData = $this->stock->getGraphData($id, 'last_month', 'Price');
            $stockPriceLava = new Lavacharts;
            $prices = \Lava::DataTable();
            $prices->addStringColumn('Date')
                ->addNumberColumn('Price')
                ->addRows($priceGraphData);

            $stockPriceLava = \Lava::AreaChart('StockPrice')
                ->dataTable($prices)
                ->setOptions([
                    'width' => 838,
                    'height' => 325,
                    'title' => 'Price of '.strtoupper($id)
                ]);

            return view('pages.individualstock')->with([
                'stockPriceLava' => $stockPriceLava,
                'stock' => Stock::where('stock_code', $id)->first(),
                'relatedStocks' => StockMetrics::getMetricsByStockList(Stock::getRelatedStocks($id), 'omit'),
                'metrics' => StockMetrics::where('stock_code', $id)->first()
            ]);
        }
        return redirect('/');
    }

    public function graph($stockCode, $timeFrame, $dataType){
        $graphData = $this->stock->getGraphData($stockCode, $timeFrame, $dataType);
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
