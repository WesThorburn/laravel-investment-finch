<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function show($id)
    {
        return view('pages.individualstock')->with([
            'stock' => Stock::where('stock_code', $id)->first(),
            'metrics' => StockMetrics::where('stock_code', $id)->get(),
            'dates' => \DB::table('historicals')->where('stock_code', $id)->lists('date'),
            'prices' => \DB::table('historicals')->where('stock_code', $id)->lists('close'),
        ]);
    }
}
