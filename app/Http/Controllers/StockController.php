<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockMetrics;

class StockController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        
        $tradingRecords = \DB::table('historicals')->select('date', 'close', 'volume')->where('stock_code', $id)->orderBy('date')->get();
        $tableRowsPrice = array();
        foreach($tradingRecords as $tradingRecord){
            array_push($tableRowsPrice, array($tradingRecord->date, $tradingRecord->close));
        }
        $tableRowsVolume = array();
        foreach($tradingRecords as $tradingRecord){
            array_push($tableRowsVolume, array($tradingRecord->date, $tradingRecord->volume));
        }

        $stockPriceChart = new Lavacharts;  // Lava::DataTable() if using Laravel
        $stockPriceData = $stockPriceChart->DataTable();
        $stockPriceData->addDateColumn('Date')
             ->addNumberColumn('Stock Price')
             ->addRows($tableRowsPrice);

        $linechart = $stockPriceChart->AreaChart('StockPrice')
                          ->dataTable($stockPriceData)
                          ->title('Stock Price of '.$id);

        $stockVolumeChart = new Lavacharts;
        $stockVolumeData = $stockVolumeChart->DataTable();
        $stockVolumeData->addDateColumn('Date')
             ->addNumberColumn('Stock Volume')
             ->addRows($tableRowsVolume);

         $barChart = $stockVolumeChart->BarChart('StockVolume')
                      ->dataTable($stockVolumeData)
                      ->title('Volume');


        return view('pages.individualstock')->with([
            'stockVolumeChart' => $stockVolumeChart,
            'stockPriceChart' => $stockPriceChart,
            'stock' => Stock::where('stock_code', $id)->first(),
            'metrics' => StockMetrics::where('stock_code', $id)->first(),
            'dates' => \DB::table('historicals')->where('stock_code', $id)->lists('date'),
            'prices' => \DB::table('historicals')->where('stock_code', $id)->lists('close'),
        ]);
    }
}
