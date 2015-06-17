<?php

class ArtisanStockCommandTest extends TestCase{
	public function testUpdateStockList(){
		$this->artisan("stocks:updateStockList");
	}

	/*public function testUpdateStockMetrics(){
		$this->artisan("stocks:updateStockMetrics");
	}*/
}