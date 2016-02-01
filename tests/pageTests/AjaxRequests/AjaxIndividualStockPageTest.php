<?php

use App\Models\Historicals;

class AjaxIndividualStockPageTest extends TestCase{
	public function testCurrentStockPrice(){
		$this->visit('ajax/stock/currentPrice/CBA');
	}

	public function testDayChange(){
		$this->visit('ajax/stock/dayChange/CBA');
	}

	public function testRelatedStocks(){
		$this->visit('/ajax/relatedstocks/CBA')
			->see('Related Stocks')
			->see('ANZ')
			->see('Australia And New Zealand Banking Group Limited')
			->see('WBC')
			->see('Westpac Banking Corporation');
	}

	public function testStockGraph(){
		$mostRecentDateInGraph = Historicals::getMostRecentHistoricalDate('TLS');
		$mostRecentMonth = jdmonthname(explode("-", $mostRecentDateInGraph)[1], 2);
		$mostRecentYear = explode("-", $mostRecentDateInGraph)[0];

		$this->visit('ajax/graph/stock/TLS/last_month/Price')
			->see('Date')
			->see('Price')
			->see($mostRecentMonth)
			->see($mostRecentYear);

		$this->visit('ajax/graph/stock/TLS/all_time/Price')
			->see('Date')
			->see('Price')
			->see('Feb 1, 2000')
			->see($mostRecentMonth)
			->see($mostRecentYear);
	}
}