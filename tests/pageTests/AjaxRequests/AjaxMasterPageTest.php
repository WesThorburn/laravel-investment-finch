<?php

class AjaxMasterPageTest extends TestCase{
	public function testMarketStatus(){
		$this->visit('/marketstatus')
			->see("Market")
			->see("(Sydney)");
	}

	public function testMarketChange(){
		$this->visit('/marketchange')
			->see('The ASX')
			->see("%");
	}
}