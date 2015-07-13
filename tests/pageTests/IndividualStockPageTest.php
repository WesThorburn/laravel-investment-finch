<?php

class IndividualStockPageTest extends TestCase{
	public function testIndividualStockPage(){
		$this->visit('/stock/CBA')
			->see("COMMONWEALTH")
			->see("Bank")
			->see("CBA")
			->see("Key Metrics")
			->see("Price of CBA")
			->see("Related Stocks");
	}
}