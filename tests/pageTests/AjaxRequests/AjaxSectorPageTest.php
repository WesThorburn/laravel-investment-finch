<?php

use App\Models\SectorIndexHistoricals;

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
		$mostRecentDateInGraph = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
		$mostRecentMonth = getMonthNameFromNumber(explode("-", $mostRecentDateInGraph)[1]);
		$this->visit('ajax/graph/sector/Banks/last_month/Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Cap"}],"rows"')
			->see($mostRecentMonth);

		$this->visit('ajax/graph/sector/Banks/last_6_months/Cap')
			->see('{"cols":[{"type":"string","label":"Date"},{"type":"number","label":"Cap"}],"rows"')
			->see($mostRecentMonth);
	}
}