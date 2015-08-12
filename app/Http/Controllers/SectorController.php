<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;

class SectorController extends Controller
{
	public function index(){
		return redirect('/sectors/'.SectorHistoricals::getBestPerformingSector());
	}

    public function show($sectorName)
    {
        return view('pages.sectors')->with([
        	'selectedSector' => $sectorName,
            'selectedSectorDayChange' => SectorHistoricals::getSelectedSectorDayChange($sectorName),
        	'sectors' => SectorHistoricals::getSectorDayChanges("sectorDayGain", 30),
        	'sectorWeekDay' => SectorHistoricals::getSectorWeekDay(),
        	'stocksInSector' => StockMetrics::getMetricsByStockList(Stock::where('sector', $sectorName)->lists('stock_code'), 'all'),
        ]);
    }

    public function sectorDayChanges($sectorName){
        return view('layouts.partials.all-sectors-day-change-display')->with([
            'selectedSector' => $sectorName,
            'sectors' => SectorHistoricals::getSectorDayChanges("sectorDayGain", 30),
            'sectorWeekDay' => SectorHistoricals::getSectorWeekDay()
        ]);
    }

    public function otherStocksInSector($sectorName){
        return view('layouts.partials.other-stocks-in-sector')->with([
            'selectedSector' => $sectorName,
            'stocksInSector' => StockMetrics::getMetricsByStockList(Stock::where('sector', $sectorName)->lists('stock_code'), 'all')
        ]);
    }
}
