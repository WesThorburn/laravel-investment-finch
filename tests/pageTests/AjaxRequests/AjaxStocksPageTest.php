<?php

class AjaxStocksPageTest extends TestCase{

	public function testMarketIndexes(){
		
		$marketIndexes = ['all', 'asx20', 'asx50', 'asx100', 'asx200', 'asx300', 'allOrds'];

		foreach($marketIndexes as $index){
			$this->visit('/ajax/stocks/'.$index)
				->see('{"draw":0,"recordsTotal"')
				->assertResponseStatus(200);
		}
	}
}