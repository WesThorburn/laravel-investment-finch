<?php

class NavigationTest extends TestCase{
	public function testHomeButton(){
		$this->visit('/search')
			->click('Home')
			->seePageIs('/');
	}

	public function testSectorsButton(){
		//'Sectors' redirects to URL of day's highest performing sector
		$this->visit('/')
			->click('Sectors')
			->see('Sector')
			->see('Change');
	}

	public function testScreenerButton(){
		$this->visit('/')
			->click('Search')
			->seePageIs('/search');
	}
}