<?php

use App\Models\SectorHistoricals;

class AjaxSectorPageTest extends TestCase{
	public function testSectorDayChanges(){
		$this->visit('/ajax/sectors/Banks/daychanges')
			->see('Sector')->see('Change')
			->dontSee('Toggle navigation');
			//Toggle Navigation appears if the whole page is reloaded.
	}

	public function testOtherStocksInSector(){
		$this->visit('/ajax/sectors/Banks/otherstocksinsector')
			->see('Banks')
			->see('Code')
			->see('Name')
			->see('Share Price')
			->see('Day Change')
			->see('Mkt Cap')
			->see('ANZ')
			->see('Australia And New Zealand Banking Group Limited');
	}
	
	public function testSectorGraph(){
		$mostRecentDateInGraph = SectorHistoricals::getMostRecentSectorHistoricalsDate();
		$mostRecentMonth = jdmonthname(explode("-", $mostRecentDateInGraph)[1], 2);
		$mostRecentYear = explode("-", $mostRecentDateInGraph)[0];
		$this->visit('ajax/graph/sector/Banks/last_month/Market%20Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Market Cap"}],"rows"')
			->see($mostRecentMonth)
			->see($mostRecentYear);

		$this->visit('ajax/graph/sector/Banks/last_6_months/Market%20Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Market Cap"}],"rows"')
			->see($mostRecentMonth)
			->see($mostRecentYear);
	}
}