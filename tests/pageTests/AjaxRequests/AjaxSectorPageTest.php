<?php

class AjaxSectorPageTest extends TestCase{
	public function testSectorDayChanges(){
		$this->visit('/sectors/all/daychanges')
			->see('Sector')
			->see('Change');
	}

	public function testOtherStocksInSector(){
		$this->visit('/sectors/Bank/otherstocksinsector')
			->see('Bank')
			->see('Code')
			->see('Name')
			->see('Share Price')
			->see('Day Change')
			->see('Mkt Cap')
			->see('ANZ')
			->see('Australia And New Zealand Banking Group Limited');
	}
}