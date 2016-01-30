<?php

class StocksPageTest extends TestCase{
	public function testStocksPage(){
		$this->visit('/stocks')
			->see('Code')->see('Name')->see('Sector')
			->see('All');
	}
}