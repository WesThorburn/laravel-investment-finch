<?php

class HomePageTest extends TestCase{
	public function testIndexPage(){
		$this->visit('/')
			->see("Best Performing Sectors")->see("Worst Performing Sectors")
			->see("Best Performing Stocks (7 Days)")->see("Worst Performing Stocks (7 Days)")
			->see('Code')->see('Name')->see('Sector');
	}

	public function testSectorFilter(){
		$this->visit('/')
			->select('All','stockSector')
			->press('Filter')
			->see('Code')->see('Name')->see('Sector');
	}
}