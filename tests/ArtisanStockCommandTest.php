<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArtisanStockCommandTest extends TestCase{

	use DatabaseTransactions;
	
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
	}

	public function testGetDailyFinancials(){
		$this->artisan("stocks:getDailyFinancials", ['--testMode' => true]);
		if(isTradingDay()){
			$this->seeInDatabase('historicals', ['stock_code' => 'TLS', 'date' => date("Y-m-d")]);
			$this->seeInDatabase('historicals', ['stock_code' => 'CBA','date' => date("Y-m-d")]);
		}
	}

	public function testResetDayChange(){
		$this->artisan("stocks:resetDayChange", ['--testMode' => true]);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'TLS', 'percent_change' => '0.00']);
		$this->seeInDatabase('stock_metrics', ['stock_code' => 'CBA', 'percent_change' => '0.00']);
	}

	public function testUpdateSectorChange(){
		$this->artisan("stocks:updateSectorMetrics", ['--testMode' => true]);
		if(isTradingDay()){
			$this->seeInDatabase('sector_index_historicals', ['sector' => 'Telecommunication Services', 'date' => date("Y-m-d")]);
			$this->seeInDatabase('sector_index_historicals', ['sector' => 'Banks', 'date' => date("Y-m-d")]);
		}
	}

	public function testCalculateStockChange(){
		$this->artisan("stocks:calculateStockChange", ['--testMode' => true]);
	}

	public function testCalculateTrend(){
		$this->artisan("stocks:calculateTrend", ['--testMode' => true]);
	}

	public function testUpdateStockAnalysis(){
		$this->artisan("stocks:updateStockAnalysis", ['--testMode' => true]);
	}

	public function updatePreviousDayMarketCap(){
		$this->artisan("stocks:updatePreviousDayMarketCap", ['--testMode' => true]);
	}
}