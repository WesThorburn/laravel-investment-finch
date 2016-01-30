<?php

class AjaxHomePageTest extends TestCase{
	public function testBestPerformingSectors(){
		$this->visit('/search/%7Bsearch%7D?viewType=partial&section=sectorDayGain')
			->see("Best Performing Sectors")
			->see("%");
	}

	public function testWorstPerformingSectors(){
		$this->visit('/search/%7Bsearch%7D?viewType=partial&section=sectorDayLoss')
			->see("Worst Performing Sectors")
			->see("%");
	}
}