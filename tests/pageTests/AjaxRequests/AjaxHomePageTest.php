<?php

class AjaxHomePageTest extends TestCase{
	public function testBestPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/top')
			->see("Best Performing Sectors")
			->see("%");
	}

	public function testWorstPerformingSectors(){
		$this->visit('/ajax/sectors/topPerforming/bottom')
			->see("Worst Performing Sectors")
			->see("%");
	}
}