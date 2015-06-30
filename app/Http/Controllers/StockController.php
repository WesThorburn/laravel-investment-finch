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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        $graphData = $this->stock->getGraphData($id);
        $stockPriceLava = new Lavacharts;
        $prices = \Lava::DataTable();
        $prices->addStringColumn('Date')
            ->addNumberColumn('Price')
            ->addRows($graphData);

        $stockPriceLava = \Lava::AreaChart('StockPrice')
            ->dataTable($prices)
            ->setOptions([
                'width' => 550,
                'height' => 325,
                'title' => 'Price of '.strtoupper($id)
            ]);

        return view('pages.individualstock')->with([
            'stockPriceLava' => $stockPriceLava,
            'stock' => Stock::where('stock_code', $id)->first(),
            'metrics' => StockMetrics::where('stock_code', $id)->first()
        ]);

        /*$stockPriceLava->HorizontalAxis(array(
            'maxTextLines' => 1000,
            'showTextEvery' => 1,
            'gridlines' => array(
                'color' => '#43fc72',
                'count' => 6
            ),
            'minorGridlines' => array(
                'color' => '#b3c8d1',
                'count' => 3
            )
        ));*/
    }

    public function graph($dataType, $stockCode, $timeFrame){
        return $dataType. $stockCode. $timeFrame;
    }
}
