<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorIndexHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Khill\Lavacharts\Lavacharts;

class SectorController extends Controller
{
	public function index(){
		return redirect('/sectors/'.SectorIndexHistoricals::getBestPerformingSector());
	}

    public function show($sectorName)
    {
        $sectorGraphData = SectorIndexHistoricals::getIndividualSectorGraphData($sectorName, 'last_month', 'Market Cap');
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

        //Pie Chart For Stocks In Sector
        $marketCapsInSectorGraphData = StockMetrics::getMarketCapsInSectorGraphData($sectorName, 'top_5');
        $marketCapsInSector = \Lava::DataTable();
        $marketCapsInSector->addStringColumn('Company Name')
            ->addNumberColumn('Percent')
            ->addRows($marketCapsInSectorGraphData);

        $marketCapsInSectorLava = \Lava::PieChart('SectorStocks')
            ->dataTable($marketCapsInSector)
            ->customize([
                'tooltip' => [
                    'text' => 'percentage'
                ]
            ])
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'Stocks In Sector',
                'pieSliceText' => 'label',
            ]);

        return view('pages.sectors')->with([
            'sectorCapsLava' => $sectorCapsLava,
        	'selectedSector' => $sectorName,
            'selectedSectorDayChange' => SectorIndexHistoricals::getSelectedSectorDayChange($sectorName),
        	'sectors' => SectorIndexHistoricals::getSectorDayChanges("top", 30),
        	'sectorWeekDay' => SectorIndexHistoricals::getSectorWeekDay(),
        	'stocksInSector' => StockMetrics::getMetricsByStockList(Stock::where('sector', $sectorName)->lists('stock_code'), 'all'),
        ]);
    }

    public function sectorDayChanges($sectorName){
        return view('layouts.partials.all-sectors-day-change-display')->with([
            'selectedSector' => $sectorName,
            'sectors' => SectorIndexHistoricals::getSectorDayChanges("top", 30),
            'sectorWeekDay' => SectorIndexHistoricals::getSectorWeekDay()
        ]);
    }

    public function otherStocksInSector($sectorName){
        return view('layouts.partials.other-stocks-in-sector')->with([
            'selectedSector' => $sectorName,
            'stocksInSector' => StockMetrics::getMetricsByStockList(Stock::where('sector', $sectorName)->lists('stock_code'), 'all')
        ]);
    }

    public function topPerformingSectors($topOrBottom){
        return view('layouts.partials.sector-day-change-display')
            ->with([
                'sectorChanges' => SectorIndexHistoricals::getSectorDayChanges($topOrBottom, 5, true), 
                'title' => SectorIndexHistoricals::getSectorDayChangeTitle($topOrBottom, 5, true)
            ]);
    }
}
