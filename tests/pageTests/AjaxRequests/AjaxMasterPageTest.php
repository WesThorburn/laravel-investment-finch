<?php

use App\Models\SectorIndexHistoricals;
use Carbon\Carbon;

class AjaxMasterPageTest extends TestCase{
	public function testMarketStatus(){
		$this->visit('/ajax/marketstatus')
			->see(date('l'))
			->see(date('F'))
			->see(date('j'))
			->see(date('Y'))
			->see("(Sydney)");

		if(isMarketOpen()){
			$this->visit('/ajax/marketstatus')
				->see("Market Open");
		}
		else{
			$this->visit('/ajax/marketstatus')
				->see("Market Closed");
		}
	}

	public function testMarketChange(){
		$mostRecentHistoricalDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
		$carbonMostRecentHistoricalDate = getCarbonDateFromDate($mostRecentHistoricalDate);
		if($carbonMostRecentHistoricalDate->isToday()){
			$this->visit('/ajax/marketchange')
				->see('The ASX')
				->see("% today");
		}
		else{
			$this->visit('/ajax/marketchange')
				->see('The ASX was')
				->see('on '. date('D', strtotime("Sunday +".$carbonMostRecentHistoricalDate->dayOfWeek." days")));
		}
		
			
	}
}