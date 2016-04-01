<?php

class PortfolioPageTest extends TestCase{
	public function testPortfolioPage(){
		$this->visit('/')
			->click('Portfolio')
			->see('Your Portfol2ios');
	}
}