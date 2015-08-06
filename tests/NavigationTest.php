<?php

class NavigationTest extends TestCase{
	public function testHomeButton(){
		$this->visit('/search')
			->click('Home')
			->seePageIs('/');
	}

	public function testSectorsButton(){
		$this->visit('/')
			->click('Sectors')
			->seePageIs('/sectors');
	}

	public function testScreenerButton(){
		$this->visit('/')
			->click('Search')
			->seePageIs('/search');
	}
}