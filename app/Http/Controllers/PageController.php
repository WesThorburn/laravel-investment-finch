<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SectorHistoricals;
use App\Models\StockGains;

class PageController extends Controller
{
    public function index()
    {
        return view('pages.home')->with([
            'sectorDayGains' => SectorHistoricals::getSectorDayChanges('sectorDayGain'),
            'sectorDayLosses' => SectorHistoricals::getSectorDayChanges('sectorDayLoss'),
            'sectorDayGainTitle' => SectorHistoricals::getSectorDayChangeTitle('sectorDayGain'),
            'sectorDayLossTitle' => SectorHistoricals::getSectorDayChangeTitle('sectorDayLoss'),
            'topWeeklyGains' => StockGains::getBestPerformingStocksThisWeek(),
            'topWeeklyLosses' => StockGains::getWorstPerformingStocksThisWeek(),
            'topStocksThisYear' => StockGains::getBestPerformingStocksThisYear()
        ]);
    }
}
