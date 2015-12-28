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
        $marketGraphData = SectorHistoricals::getGraphData('All', 'last_month', 'Market Cap');
            $marketCaps = \Lava::DataTable();
            $marketCaps->addStringColumn('Date')
                ->addNumberColumn('Market Cap')
                ->addRows($marketGraphData);

        $marketCapsLava = \Lava::AreaChart('MarketCaps')
            ->dataTable($marketCaps)
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'Total Market Cap (Billions)'
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
            'topStocksThisYear' => StockGains::getTopStocksThisYear(29)
        ]);
    }
}
