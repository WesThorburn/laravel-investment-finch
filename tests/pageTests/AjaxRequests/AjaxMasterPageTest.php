<?php

class AjaxMasterPageTest extends TestCase{
	public function testMarketStatus(){
		$this->visit('/ajax/marketstatus')
			->see("Market")
			->see("(Sydney)");
	}

	public function testMarketChange(){
		$this->visit('/ajax/marketchange')
			->see('The ASX')
			->see("%");
	}
}