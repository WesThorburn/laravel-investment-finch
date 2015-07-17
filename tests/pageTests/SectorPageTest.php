<?php

class SectorPageTest extends TestCase{
	public function testSectorPage(){
		$this->visit('/sector')
			->see("Sector")->see("Change")
			->click("Bank")
			->seePageIs("/sector/Bank")
			->see("ANZ")->see("Australia and new zealand banking group limited");		
	}

	public function testStockLink(){
		$this->visit("/sector/Bank")
			->click("ANZ")
			->seePageIs("/stock/ANZ")
			->see("AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED")
			->see("(ASX: ANZ)")
			->see("Price of ANZ")
			->see("Key Metrics")
			->see("Related Stocks");
	}
}