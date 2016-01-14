<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\StockGains;
use App\Models\SectorHistoricals;
use App\Models\StockMetrics;
use Khill\Lavacharts\Lavacharts;

class PageController extends Controller
{
    public function index()
    {
        //Line graph for market cap
        $totalMarketCapGraphData = SectorHistoricals::getIndividualSectorGraphData('All', 'last_month', 'Market Cap');
        $marketCaps = \Lava::DataTable();
        $marketCaps->addStringColumn('Date')
            ->addNumberColumn('Market Cap')
            ->addRows($totalMarketCapGraphData);

        $marketCapsLava = \Lava::AreaChart('MarketCaps')
            ->dataTable($marketCaps)
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'ASX Market Cap (Billions)'
            ]);

        //PieChart for Sectors' Market Caps
        $individualSectorCapsGraphData = SectorHistoricals::getAllSectorGraphData('top_5');
        $sectorCaps = \Lava::DataTable();
        $sectorCaps->addStringColumn('Sector Name') 
            ->addNumberColumn('Percent')
            ->addRows($individualSectorCapsGraphData);

        $sectorCapsLava = \Lava::PieChart('Sectors')
            ->dataTable($sectorCaps)
            ->customize([
            	'tooltip' => [
            		'text' => 'percentage'
            	]
            ])
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'Sector Caps (Billions)',
                'pieSliceText' => 'label',
            ]);

        return view('pages.home')->with([
            'marketCapsLava' => $marketCapsLava,
            'sectorDayGains' => SectorHistoricals::getSectorDayChanges('sectorDayGain'),
            'sectorDayLosses' => SectorHistoricals::getSectorDayChanges('sectorDayLoss'),
            'sectorDayGainTitle' => SectorHistoricals::getSectorDayChangeTitle('sectorDayGain'),
            'sectorDayLossTitle' => SectorHistoricals::getSectorDayChangeTitle('sectorDayLoss')
        ]);
    }

    public function topGainsLosses(){
        $allNonOmittedStocks = StockMetrics::omitOutliers()->lists('stock_code');

        return view('pages.topGainsLosses')->with([
            'topWeeklyGains' => StockGains::getTopStocksThisWeek($allNonOmittedStocks),
            'topWeeklyLosses' => StockGains::getBottomStocksThisWeek($allNonOmittedStocks),
            'topMonthlyGains' => StockGains::getTopStocksThisMonth($allNonOmittedStocks),
            'topMonthlyLosses' => StockGains::getBottomStocksThisMonth($allNonOmittedStocks),
            'topStocks12Months' => StockGains::getTopStocks12Months(29)
        ]);
    }
}
