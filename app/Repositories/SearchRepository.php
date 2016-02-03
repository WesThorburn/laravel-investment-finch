<?php namespace App\Repositories;

use App\Models\Stock;
use App\Models\StockMetrics;

Class SearchRepository implements SearchRepositoryInterface{
	public function getAllMetrics($omitCondition){
		return StockMetrics::with('stock')->where('stock_code', '!=', 'null')->omitOutliers($omitCondition)->get();
	}

	public function getSearchResults($request){
		$allSectors = [];
		$minPrice = StockMetrics::min('last_trade');
		$maxPrice = StockMetrics::max('last_trade');
		$minVolume = StockMetrics::min('volume');
		$maxVolume = StockMetrics::max('volume');
		$minEBITDA = StockMetrics::min('EBITDA');
		$maxEBITDA = StockMetrics::max('EBITDA');
		$minEPSCurrentYear = StockMetrics::min('earnings_per_share_current');
		$maxEPSCurrentYear = StockMetrics::max('earnings_per_share_current');
		$minEPSNextYear = StockMetrics::min('earnings_per_share_next_year');
		$maxEPSNextYear = StockMetrics::max('earnings_per_share_next_year');
		$minPERatio = StockMetrics::min('price_to_earnings');
		$maxPERatio = StockMetrics::max('price_to_earnings');
		$minPriceBook = StockMetrics::min('price_to_book');
		$maxPriceBook = StockMetrics::max('price_to_book');
		$min52WeekHigh = StockMetrics::min('year_high');
		$max52WeekHigh = StockMetrics::max('year_high');
		$min52WeekLow = StockMetrics::min('year_low');
		$max52WeekLow = StockMetrics::max('year_low');
		$minMarketCap = StockMetrics::min('market_cap');
		$maxMarketCap = StockMetrics::max('market_cap');
		$minDividendYield = StockMetrics::min('dividend_yield');
		$maxDividendYield = StockMetrics::max('dividend_yield');
		
		if($request->minPrice != null){
			$minPrice = $request->minPrice;
		}

		if($request->maxPrice != null){
			$maxPrice = $request->maxPrice;
		}

		if($request->minVolume != null){
			$minVolume = $request->minVolume;
		}

		if($request->maxVolume != null){
			$maxVolume = $request->maxVolume;
		}

		if($request->minEBITDA != null){
			$minEBITDA = $request->minEBITDA;
		}

		if($request->maxEBITDA != null){
			$maxEBITDA = $request->maxEBITDA;
		}

		if($request->minEPSCurrentYear != null){
			$minEPSCurrentYear = $request->minEPSCurrentYear;
		}

		if($request->maxEPSCurrentYear != null){
			$maxEPSCurrentYear = $request->maxEPSCurrentYear;
		}

		if($request->minEPSNextYear != null){
			$minEPSNextYear = $request->minEPSNextYear;
		}

		if($request->maxEPSNextYear != null){
			$maxEPSNextYear = $request->maxEPSNextYear;
		}

		if($request->minPERatio != null){
			$minPERatio = $request->minPERatio;
		}

		if($request->maxPERatio != null){
			$maxPERatio = $request->maxPERatio;
		}

		if($request->minPriceBook != null){
			$minPriceBook = $request->minPriceBook;
		}

		if($request->maxPriceBook != null){
			$maxPriceBook = $request->maxPriceBook;
		}

		if($request->min52WeekHigh != null){
			$min52WeekHigh = $request->min52WeekHigh;
		}

		if($request->max52WeekHigh != null){
			$max52WeekHigh = $request->max52WeekHigh;
		}

		if($request->min52WeekLow != null){
			$min52WeekLow = $request->min52WeekLow;
		}

		if($request->max52WeekLow != null){
			$max52WeekLow = $request->max52WeekLow;
		}

		if($request->minMarketCap != null){
			$minMarketCap = $request->minMarketCap;
		}

		if($request->maxMarketCap != null){
			$maxMarketCap = $request->maxMarketCap;
		}

		if($request->minDividendYield != null){
			$minDividendYield = $request->minDividendYield;
		}

		if($request->maxDividendYield != null){
			$maxDividendYield = $request->maxDividendYield;
		}

		if($request->stockSector == null){
			$request->stockSector = "All";
		}

		//Omit results if box isn't ticked.
		if($request->omitCondition == null){
			$request->omitCondition = 'omit';
		}
		
		return StockMetrics::whereIn('stock_code', $this->getStocksBySector($request->stockSector))
			->whereBetween('last_trade', [$minPrice, $maxPrice])
			->whereBetween('volume', [$minVolume, $maxVolume])
			->whereBetween('EBITDA', [$minEBITDA, $maxEBITDA])
			->whereBetween('earnings_per_share_current', [$minEPSCurrentYear, $maxEPSCurrentYear])
			->whereBetween('earnings_per_share_next_year', [$minEPSNextYear, $maxEPSNextYear])
			->whereBetween('price_to_earnings', [$minPERatio, $maxPERatio])
			->whereBetween('price_to_book', [$minPriceBook, $maxPriceBook])
			->whereBetween('year_high', [$min52WeekHigh, $max52WeekHigh])
			->whereBetween('year_low', [$min52WeekLow, $max52WeekLow ])
			->whereBetween('market_cap', [$minMarketCap, $maxMarketCap])
			->whereBetween('dividend_yield', [$minDividendYield, $maxDividendYield])
			->omitOutliers($request->omitCondition)
			->lists('stock_code');
			
	}

	private function getStocksBySector($sectorRequest){
		if($sectorRequest == "All"){
			return Stock::distinct('sector')->lists('stock_code');
		}
		return Stock::where('sector', $sectorRequest)->lists('stock_code');
	}
}