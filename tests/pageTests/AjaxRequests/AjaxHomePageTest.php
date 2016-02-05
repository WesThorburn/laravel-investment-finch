<?php

use App\Models\SectorIndexHistoricals;

class AjaxHomePageTest extends TestCase{
	public function testBestPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/top')
			->see("Best Performing Sectors")
			->see("%");
	}

	public function testWorstPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/bottom')
			->see("Worst Performing Sectors")
			->see("%");
	}

	public function testASXMarketCapLineGraph(){
		$mostRecentDateInGraph = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
		$mostRecentMonth = jdmonthname(explode("-", $mostRecentDateInGraph)[1], 2);
		$mostRecentYear = explode("-", $mostRecentDateInGraph)[0];
		$this->visit('/ajax/graph/sector/All/last_month/Market%20Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Market Cap"}],"rows"')
			->see($mostRecentMonth)
			->see($mostRecentYear);

		$this->visit('/ajax/graph/sector/All/all_time/Market%20Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Market Cap"}],"rows"')
			->see($mostRecentMonth)
			->see($mostRecentYear);
	}

	public function testSectorCapsPieChart(){
		$this->visit('/ajax/graph/sectorPie/5')
			->see('cols')->see('"type":"string","label":"Sector Name"')->see('"type":"number","label":"Percent"')
			->see("Banks")->see("Materials");

		$this->visit('/ajax/graph/sectorPie/all')
			->see('cols')->see('"type":"string","label":"Sector Name"')->see('"type":"number","label":"Percent"')
			->see("Banks")->see("Materials");
	}
}