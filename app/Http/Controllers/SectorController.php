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
		return $this->show(SectorHistoricals::getBestPerformingSector());
	}

    public function show($sectorName)
    {
    	$sectorDayChanges = SectorHistoricals::getSectorDayChanges("sectorDayGain", 30);
        return view('pages.sectors')->with([
        	'selectedSector' => $sectorName,
        	'sectors' => $sectorDayChanges,
        	'sectorDayChangeDay' => SectorHistoricals::getSectorDayChangeDay(),
        	'stocksInSector' => StockMetrics::getMetricsByStockList(Stock::where('sector', $sectorName)->lists('stock_code'), 'omit'),
        ]);
    }
}
