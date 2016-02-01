<?php

class NavigationTest extends TestCase{
	public function testHomePage(){
		$this->visit('/')
			->assertResponseOk();
	}

	public function testSectorsPage(){
		//'Sectors' redirects to URL of day's highest performing sector
		$this->visit('/')
			->click('Sectors')
			->see('Sector')
			->see('Change');
	}

	public function testStocksPage(){
		$this->visit('/')
			->click('Stocks')
			->seePageIs('/index/all')
			->see('All ASX Stocks');
	}

	public function testStocksPageIndexes(){
		$this->visit('/index/all')
			->click('ASX 20')
			->seePageIs('/index/asx20')
			->see('ASX 20 | Top 20 Stocks')

			->click('ASX 50')
			->seePageIs('/index/asx50')
			->see('ASX 50 | Top 50 Stocks')

			->click('ASX 100')
			->seePageIs('/index/asx100')
			->see('ASX 100 | Top 100 Stocks')

			->click('ASX 200')
			->seePageIs('/index/asx200')
			->see('ASX 200 | Top 200 Stocks')

			->click('ASX 300')
			->seePageIs('/index/asx300')

			->click('All Ords')
			->seePageIs('/index/allOrds');
	}

	public function testGainsAndLossesPage(){
		$this->visit('/')
			->click('Gains/Losses')
			->assertResponseOk();
	}
}