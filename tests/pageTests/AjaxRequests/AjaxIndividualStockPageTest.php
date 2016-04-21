<?php

use App\Models\Historicals;

class AjaxIndividualStockPageTest extends TestCase{
	public function testStockChange(){
		$this->visit('ajax/stock/stockChange/CBA');
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
		$mostRecentMonth = getMonthNameFromNumber(explode("-", $mostRecentDateInGraph)[1]);
		$mostRecentYear = explode("-", $mostRecentDateInGraph)[0];

		$this->visit('ajax/graph/stock/TLS/last_month/Price')
			->see('Date')
			->see('Price')
			->see($mostRecentMonth);

		$this->visit('ajax/graph/stock/TLS/all_time/Price')
			->see('Date')
			->see('Price')
			->see('1 Feb')
			->see($mostRecentMonth);
	}
}