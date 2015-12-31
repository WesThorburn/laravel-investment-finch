<?php

class HomePageTest extends TestCase{
	public function testIndexPage(){
		$this->visit('/')
			->see("ASX Market Cap (Billions)")->see("Market Cap")
			->see("Best Performing Sectors")->see("Worst Performing Sectors");
	}

	public function testStockFind(){
		$this->visit('/')
			->type('CBA', 'stockCodeFind')
			->press('Find')
			->seePageIs('/stocks/CBA')
			->see('Commonwealth Bank of Australia')
			->see('Key Metrics')
			->see('Business Summary')
			->see('Related Stocks');
	}
}