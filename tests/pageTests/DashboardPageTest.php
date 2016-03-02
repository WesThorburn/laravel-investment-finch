<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardPageTest extends TestCase{

	use WithoutMiddleware;
	use DatabaseTransactions;

	public function testAddStockForAdjustment(){
		$this->visit('/dashboard/marketCapAdjustments/CBA/1')
			->seePageIs('/dashboard/marketCapAdjustments');
	}

	public function testRemoveStockFromAdjustmentList(){
		$this->visit('/dashboard/marketCapAdjustments/CBA/0')
			->seePageIs('/dashboard/marketCapAdjustments');
	}
}