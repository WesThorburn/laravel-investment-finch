<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PortfolioPageTest extends TestCase{

	use DatabaseTransactions;

	public function testPortfolioPage(){
		$user = factory(App\Models\User::class)->make();

		$this->actingAs($user)
			->visit('/')
			->click('Portfolio')
			->see('Your Portfolios')
			->see('Create a Portfolio');
	}
}