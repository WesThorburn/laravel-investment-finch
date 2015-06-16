<?php

class NavigationTest extends TestCase{
	public function testHomeButton(){
		$this->visit('/search')
			->click('Home')
			->seePageIs('/');
	}

	public function testScreenerButton(){
		$this->visit('/')
			->click('Screener')
			->seePageIs('/search');
	}
}