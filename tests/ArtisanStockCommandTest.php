<?php

class ArtisanStockCommandTest extends TestCase{
	public function testUpdateStockList(){
		$this->artisan("stocks:updateStockList", ['--testMode' => true]);
		$this->seeInDatabase('stocks', [
			'stock_code' => 'TLS', 
			'company_name' => 'TELSTRA CORPORATION LIMITED.'
		]);
		$this->seeInDatabase('stocks', [
			'stock_code' => 'CBA', 
			'company_name' => 'COMMONWEALTH BANK OF AUSTRALIA.'
		]);
	}

	public function testUpdateStockMetrics(){
		$this->artisan("stocks:updateStockMetrics", ['--testMode' => true]);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'TLS']);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'CBA']);
	}

	public function testGetDailyFinancials(){
		$this->artisan("stocks:getDailyFinancials", ['--testMode' => true]);
		$this->seeInDatabase('historicals', ['stock_code' => 'TLS', 'date' => date("Y-m-d")]);
		$this->seeInDatabase('historicals', ['stock_code' => 'CBA','date' => date("Y-m-d")]);
	}

	public function testResetDayChange(){
		$this->artisan("stocks:resetDayChange", ['--testMode' => true]);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'TLS', 'day_change' => '0.00']);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'CBA', 'day_change' => '0.00']);
	}

	public function testUpdateSectorChange(){
		$this->artisan("stocks:updateSectorChange", ['--testMode' => true]);
		$this->seeInDatabase('sector_historicals', ['sector' => 'Telecommunication Service', 'date' => date("Y-m-d")]);
		$this->seeInDatabase('sector_historicals', ['sector' => 'Bank', 'date' => date("Y-m-d")]);
	}

	public function testCalculateStockChange(){
		$this->artisan("stocks:calculateStockChange", ['--testMode' => true]);
		$this->seeInDatabase('stock_gains', ['stock_code' => 'TLS']);
		$this->seeInDatabase('stock_gains', ['stock_code' => 'CBA']);
	}
}