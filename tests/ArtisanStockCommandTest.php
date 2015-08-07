<?php

class ArtisanStockCommandTest extends TestCase{
	public function testUpdateStockList(){
		$this->artisan("stocks:updateStockList", ['--testMode' => true]);
		/*$this->seeInDatabase('stocks', [
			'stock_code' => 'TLS', 
			'company_name' => 'TELSTRA CORPORATION LIMITED.', 
			'updated_at' => date("Y-m-d H:i:s")
		]);
		$this->seeInDatabase('stocks', [
			'stock_code' => 'CBA', 
			'company_name' => 'COMMONWEALTH BANK OF AUSTRALIA.',
			'updated_at' => date("Y-m-d H:i:s")
		]);*/
	}

	public function testUpdateStockMetrics(){
		//$this->artisan("stocks:updateStockMetrics");
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'TLS']);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'CBA']);
	}

	public function resetDayChange(){
		//$this->artisan("stocks:resetDayChange");
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'TLS', 'day_change' => '0.00']);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'CBA', 'day_change' => '0.00']);
	}
}