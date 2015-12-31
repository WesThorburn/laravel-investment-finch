<?php

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
		$this->visit('/sectorGraph/Banks/last_month/Market%20Cap')
			->see('Date')
			->see('Market Cap')
			->see(date('M'))
			->see(date('Y'));

		$this->visit('/sectorGraph/Banks/last_6_months/Market%20Cap')
			->see('Date')
			->see('Market Cap')
			->see(date('M'))
			->see(date('Y'));
	}
}