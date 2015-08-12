<?php

class StocksPageTest extends TestCase{
	public function testStocksPage(){
		$this->visit('/stocks')
			->see('Code')->see('Name')->see('Sector')
			->see('Showing 1 to 20 of');
	}

	public function testSectorFilter(){
		$this->visit('/stocks')
			->select('All','stockSector')
			->press('Filter')
			->see('Code')->see('Name')->see('Sector');
	}
}