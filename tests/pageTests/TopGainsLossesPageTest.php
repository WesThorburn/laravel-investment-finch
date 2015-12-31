<?php

class TopGainsLossesPageTest extends TestCase{
	public function testTopGainsLossesPage(){
		$this->visit('/topGainsLosses')
			->see("Best Performing Stocks (12 Months)")
			->see("Best Performing Stocks (7 Days)")->see("Worst Performing Stocks (7 Days)")
			->see("Best Performing Stocks (30 Days)")->see("Worst Performing Stocks (30 Days)");
	}
}