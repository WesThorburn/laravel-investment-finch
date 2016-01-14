<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Khill\Lavacharts\Lavacharts;

class SectorController extends Controller
{
	public function index(){
		return redirect('/sectors/'.SectorHistoricals::getBestPerformingSector());
	}

    public function show($sectorName)
    {
        $sectorGraphData = SectorHistoricals::getIndividualSectorGraphData($sectorName, 'last_month', 'Market Cap');
        $sectorCaps = \Lava::DataTable();
        $sectorCaps->addStringColumn('Date')
            ->addNumberColumn('Sector Cap')
            ->addRows($sectorGraphData);

        $sectorCapsLava = \Lava::AreaChart('SectorCaps')
            ->dataTable($sectorCaps)
            ->setOptions([
                'width' => 725,
                'height' => 320,
                'title' => 'Total Sector Cap (Billions)'
            ]);

        return view('pages.sectors')->with([
            'sectorCapsLava' => $sectorCapsLava,
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
