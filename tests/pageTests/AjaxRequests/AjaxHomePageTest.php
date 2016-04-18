<?php

use App\Models\SectorIndexHistoricals;

class AjaxHomePageTest extends TestCase{
	public function testBestPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/top')
			->see("Best Performing Sectors")
			->see("%")
			->dontSee("Class Pend")
			->dontSee("Not Applic");
	}

	public function testWorstPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/bottom')
			->see("Worst Performing Sectors")
			->see("%")
			->dontSee("Class Pend")
			->dontSee("Not Applic");
	}

	public function testASXMarketCapLineGraph(){
		$mostRecentDateInGraph = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
		$mostRecentMonth = getMonthNameFromNumber(explode("-", $mostRecentDateInGraph)[1]);
		$this->visit('/ajax/graph/sector/All/last_month/Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Cap"}],"rows"')
			->see($mostRecentMonth);

		$this->visit('/ajax/graph/sector/All/all_time/Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Cap"}],"rows"')
			->see($mostRecentMonth);
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