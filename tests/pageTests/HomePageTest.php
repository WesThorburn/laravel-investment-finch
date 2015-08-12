<?php

class HomePageTest extends TestCase{
	public function testIndexPage(){
		$this->visit('/')
			->see("Best Performing Sectors")->see("Worst Performing Sectors")->see("Best Performing Stocks ".date("Y"))
			->see("Best Performing Stocks (7 Days)")->see("Worst Performing Stocks (7 Days)")
			->see("Best Performing Stocks (30 Days)")->see("Worst Performing Stocks (30 Days)");
	}
}