<?php

class PortfolioPageTest extends TestCase{

	public function testPortfolioPage(){
		$user = factory(App\User::class)->make();
		
		$this->actingAs($user)
			->visit('/')
			->click('Portfolio')
			->see('Your Portfolios');
	}
}