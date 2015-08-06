<?php

class SectorPageTest extends TestCase{
	public function testSectorPage(){
		$this->visit('/sectors')
			->see("Sector")->see("Change")
			->click("Bank")
			->seePageIs("/sectors/Bank")
			->see("ANZ")->see("Australia and new zealand banking group limited");		
	}

	public function testStockLink(){
		$this->visit("/sectors/Bank")
			->click("ANZ")
			->seePageIs("/stocks/ANZ")
			->see("AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED")
			->see("(ASX: ANZ)")
			->see("Price of ANZ")
			->see("Key Metrics")
			->see("Related Stocks");
	}
}