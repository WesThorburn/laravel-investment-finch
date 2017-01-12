<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Stock;
use App\Models\StockGains;
use App\Models\Historicals;
use App\Models\SectorIndexHistoricals;
use App\Models\StockMetrics;
use Khill\Lavacharts\Lavacharts;

class PageController extends Controller
{
    public function index()
    {
        //Line graph for market cap
        $totalMarketCapGraphData = SectorIndexHistoricals::getIndividualSectorGraphData('All', 'last_3_months', 'Cap');
        $marketCaps = \Lava::DataTable();
        $marketCaps->addStringColumn('Date')
            ->addNumberColumn('Cap')
            ->addRows($totalMarketCapGraphData);

        $marketCapsLava = \Lava::AreaChart('MarketCaps')
            ->dataTable($marketCaps)
            ->customize([
                 'chartArea' => [
                    'top' => 25,
                ]
            ])
            ->setOptions([
                'title' => 'ASX Market Cap (Millions)'
            ]);

        //PieChart for Sectors' Market Caps
        $individualSectorCapsGraphData = SectorIndexHistoricals::getAllSectorGraphData('top_5');
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
                'title' => 'Sector Caps',
                'pieSliceText' => 'label',
            ]);

        //Trending Stocks
        $trendingStockCodes = \DB::table('trends')->orderBy('trend_type', 'DESC')->take(15)->lists('stock_code');
        $trendingStocksmetrics = StockMetrics::whereIn('stock_code', $trendingStockCodes)->get();

        return view('pages.home')->with([
            'marketCapsLava' => $marketCapsLava,
            'sectorDayGains' => SectorIndexHistoricals::getSectorDayChanges('top', 5, true),
            'sectorDayLosses' => SectorIndexHistoricals::getSectorDayChanges('bottom', 5, true),
            'sectorDayGainTitle' => SectorIndexHistoricals::getSectorDayChangeTitle('top'),
            'sectorDayLossTitle' => SectorIndexHistoricals::getSectorDayChangeTitle('bottom'),
            'highestVolumeStocks' => StockMetrics::with('stock')->omitOutliers()->orderBy('volume', 'desc')->take(5)->get(),
            'highestVolumeStocksTitle' => SectorIndexHistoricals::getSectorWeekDay()."'s Market Movers",
            'trendingStocks' => $trendingStocksmetrics
        ]);
    }

    public function performance(){
        $allNonOmittedStocks = StockMetrics::omitOutliers()->lists('stock_code');

        return view('pages.performance')->with([
            'topWeeklyGains' => StockGains::getTopStocksThisWeek($allNonOmittedStocks),
            'topWeeklyLosses' => StockGains::getBottomStocksThisWeek($allNonOmittedStocks),
            'topMonthlyGains' => StockGains::getTopStocksThisMonth($allNonOmittedStocks),
            'topMonthlyLosses' => StockGains::getBottomStocksThisMonth($allNonOmittedStocks),
            'topStocks12Months' => StockGains::getTopStocks12Months(29)
        ]);
    }

    public function about(){
        return view('pages.about');
    }

    public function privacy(){
        return view('pages.privacy');
    }

    public function contact(){
        return view('pages.contact');
    }
}
