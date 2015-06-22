<?php

class SearchPageTest extends TestCase{
	public function testSearchPage(){
		$this->visit('/search')
			->see("Enter the metrics you'd like to search for")
			->see("Sector")
			->see("Stock Price");
	}

	public function testStockScreener(){
		$this->visit('/search')
			->select('All','stockSector')
			->type('1','minPrice')
			->type('10','maxPrice')
			->type('10000','minVolume')
			->type('9999999','maxVolume')
			->type('1','minEBITDA')
			->type('9999999','maxEBITDA')
			->type('0.05','minEPSCurrentYear')
			->type('5','maxEPSCurrentYear')
			->type('1','minPERatio')
			->type('1200','maxPERatio')
			->type('0.3','minPriceBook')
			->type('20','maxPriceBook')
			->type('0.1','min52WeekHigh')
			->type('90','max52WeekHigh')
			->type('0.1','min52WeekLow')
			->type('60','max52WeekLow')
			->type('0.1','min50DayMA')
			->type('90','max50DayMA')
			->type('0.1','min200DayMA')
			->type('70','max200DayMA')
			->type('1','minMarketCap')
			->type('1000','maxMarketCap')
			->press('Search')
			->see('Code')->see('Name')->see('Sector');
	}
}