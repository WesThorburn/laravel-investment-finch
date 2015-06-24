<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Carbon\Carbon;

class StockController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        $dailyTradingData = Historicals::where('stock_code', $id)->get();
        return view('pages.individualstock')->with([
            'stock' => Stock::where('stock_code', $id)->first(),
            'metrics' => StockMetrics::where('stock_code', $id)->first(),
            'dates' => $dailyTradingData->lists('date')->toArray(),
            'prices' => $dailyTradingData->lists('close')->toArray(),
            'volume' => $dailyTradingData->lists('volume')->toArray()
        ]);
    }
}
