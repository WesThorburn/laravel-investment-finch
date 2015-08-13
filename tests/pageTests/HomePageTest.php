<?php

class HomePageTest extends TestCase{
	public function testIndexPage(){
		$this->visit('/')
			->see("Best Performing Sectors")->see("Worst Performing Sectors")->see("Best Performing Stocks ".date("Y"))
			->see("Best Performing Stocks (7 Days)")->see("Worst Performing Stocks (7 Days)")
			->see("Best Performing Stocks (30 Days)")->see("Worst Performing Stocks (30 Days)");
	}

	public function testStockSearch(){
		$this->visit('/')
			->type('CBA', 'stockCodeSearch')
			->press('Search')
			->seePageIs('/stocks/CBA')
			->see('Commonwealth Bank of Australia')
			->see('Key Metrics')
			->see('Business Summary')
			->see('Related Stocks');
	}
}