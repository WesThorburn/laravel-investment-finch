<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorIndexHistoricals;

class MarketController extends Controller
{
    public function change(){
		return view('layouts.partials.market-change-display')
			->with([
				'marketChangeMessage' => SectorIndexHistoricals::getMarketChangeMessage(),
				'marketChange' => SectorIndexHistoricals::getMarketChange()
			]);
	}

	public function status(){
		if(isMarketOpen()){
			$marketStatus = "Market Open";
		}
		else{
			$marketStatus = "Market Closed";
		}
		return view('layouts.partials.market-status-display')
			->with([
				'serverTime' => getServerTime(),
				'marketStatus' => $marketStatus
			]);
	}
}
