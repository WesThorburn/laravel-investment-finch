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
				'marketChange' => $this->simpleChange()
			]);
	}

	public function status(){
		return view('layouts.partials.market-status-display')
			->with([
				'serverTime' => getServerTime(),
				'marketStatus' => $this->openClosed()
			]);
	}

	public function openClosed(){
		if(isMarketOpen()){
			return "Market Open";
		}
		else{
			return "Market Closed";
		}
	}

	public function time(){
		return getServerTime();
	}

	public function simpleChange(){
		return SectorIndexHistoricals::getMarketChange();
	}
}
