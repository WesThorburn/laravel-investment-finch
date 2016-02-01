<?php

class SectorPageTest extends TestCase{
	public function testSectorPage(){
		$this->visit('/sectors')
			->see("Sector")->see("Change")
			->click("Banks")
			->seePageIs("/sectors/Banks")
			->see("ANZ")->see("Australia and new zealand banking group limited");		
	}

	public function testStockLink(){
		$this->visit("/sectors/Banks")
			->click("ANZ")
			->seePageIs("/stocks/ANZ")
			->see("Australia And New Zealand Banking Group Limited")
			->see("(ASX: ANZ)")
			->see("Price of ANZ")
			->see("Key Metrics")
			->see("Related Stocks")
			->see("Business Summary");
	}
}