<?php

class StocksPageTest extends TestCase{
	public function testStocksPage(){
		$this->visit('/stocks')
			->see('Code')->see('Name')->see('Sector');
	}

	public function testSectorFilter(){
		$this->visit('/stocks')
			->select('All','stockSector')
			->press('Filter')
			->see('Code')->see('Name')->see('Sector');
	}
}