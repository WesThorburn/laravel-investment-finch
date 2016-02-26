<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardPageTest extends TestCase{

	use WithoutMiddleware;
	use DatabaseTransactions;

	public function testAddStockForAdjustment(){
		$this->visit('/dashboard/marketCapAdjustments')
			->type('TLS', 'stockCode')
			->press('Add')
			->assertResponseOk();
	}

	public function testRemoveStockFromAdjustmentList(){
		$this->visit('/dashboard/marketCapAdjustments')
			->press('removeFromList')
			->assertResponseOk();
	}
}