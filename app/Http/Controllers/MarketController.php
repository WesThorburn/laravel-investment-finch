<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorHistoricals;

class MarketController extends Controller
{
    public function change(){
		return view('layouts.partials.market-change-display')
			->with([
				'marketChangeMessage' => SectorHistoricals::getMarketChangeMessage(),
				'marketChange' => SectorHistoricals::getMarketChange()
			]);
	}

	public function status(){
		return view('layouts.partials.market-status-display')
			->with([
				'marketStatus' => getMarketStatus(),
				'serverTime' => getServerTime()
			]);
	}
}
