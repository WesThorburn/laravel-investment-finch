<?php

class StocksPageTest extends TestCase{
	public function testStocksPage(){
		$this->visit('/index/all')
			->see('Code')->see('Name')->see('Sector')
			->see('All');
	}
}