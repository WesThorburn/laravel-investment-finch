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
        $lava = new Lavacharts;

        $temperatures = $lava->DataTable();

        $temperatures->addDateColumn('Date')
                     ->addNumberColumn('Max Temp')
                     ->addNumberColumn('Mean Temp')
                     ->addNumberColumn('Min Temp')
                     ->addRow(array('2014-10-1', 67, 65, 62))
                     ->addRow(array('2014-10-2', 68, 65, 61))
                     ->addRow(array('2014-10-3', 68, 62, 55))
                     ->addRow(array('2014-10-4', 72, 62, 52))
                     ->addRow(array('2014-10-5', 61, 54, 47))
                     ->addRow(array('2014-10-6', 70, 58, 45))
                     ->addRow(array('2014-10-7', 74, 70, 65))
                     ->addRow(array('2014-10-8', 75, 69, 62))
                     ->addRow(array('2014-10-9', 69, 63, 56))
                     ->addRow(array('2014-10-10', 64, 58, 52))
                     ->addRow(array('2014-10-11', 59, 55, 50))
                     ->addRow(array('2014-10-12', 65, 56, 46))
                     ->addRow(array('2014-10-13', 66, 56, 46))
                     ->addRow(array('2014-10-14', 75, 70, 64))
                     ->addRow(array('2014-10-15', 76, 72, 68))
                     ->addRow(array('2014-10-16', 71, 66, 60))
                     ->addRow(array('2014-10-17', 72, 66, 60))
                     ->addRow(array('2014-10-18', 63, 62, 62));

        $linechart = $lava->LineChart('Temps')
                          ->dataTable($temperatures)
                          ->title('Weather in October');

        return view('pages.individualstock')->with([
            'linechart' => $linechart,
            'stock' => Stock::where('stock_code', $id)->first(),
            'metrics' => StockMetrics::where('stock_code', $id)->get(),
            'dates' => \DB::table('historicals')->where('stock_code', $id)->lists('date'),
            'prices' => \DB::table('historicals')->where('stock_code', $id)->lists('close'),
        ]);
    }
}
