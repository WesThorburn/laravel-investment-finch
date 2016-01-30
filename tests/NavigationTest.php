<?php

class NavigationTest extends TestCase{
	public function testHomeButton(){
		$this->visit('/sectors')
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
}