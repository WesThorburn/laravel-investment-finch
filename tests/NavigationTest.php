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
			->seePageIs('/sector');
	}

	public function testScreenerButton(){
		$this->visit('/')
			->click('Screener')
			->seePageIs('/search');
	}
}