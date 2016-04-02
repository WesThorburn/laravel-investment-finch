<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PortfolioPageTest extends TestCase{

	use DatabaseTransactions;

	public function testPortfolioPage(){
		$user = factory(App\Models\User::class)->create();

		$this->actingAs($user)
			->visit('/')
			->click('Portfolio')
			->see('Your Portfolios')
			->see('Create a Portfolio')
			->dontSee('Stocks in ')
			->dontSee('Add a Stock to this Portfolio')
			->dontSee('Your Trades');
	}

	public function testPortfolioFunctions(){
		$user = factory(App\Models\User::class)->create();

		$this->actingAs($user)
			->visit('/user/portfolio')

			//Create Portfolio
			->type('Test Portfolio', 'portfolioName')
			->press('Create')
			->see('Stocks in Test Portfolio')
			->see('Add a Stock to this Portfolio')

			//Add stock to portfolio
			->type('A2M', 'purchaseStockCode')
			->type('1.50', 'purchasePrice')
			->type('1000', 'purchaseQuantity')
			->type('07/04/2016', 'purchaseDate')
			->press('Add')

			//See Stock in portfolio row
			->see('A2M')->see('1.52')

			//See Trade Data
			->see('Your Trades')->see('Buy');

			//Sell stock
			$this->withoutMiddleware();

			$response = $this->call('PUT', '/user/portfolio/'.$user->portfolios()->first()->id, [
				'tradeType' => 'sell',
				'saleStockCode' => 'A2M',
				'salePrice' => '2.00',
				'saleQuantity' => '1000',
				'saleBrokerage' => '19.95',
				'saleDate' => '07/04/2016'
			]);

			$this->assertResponseStatus(302);
	}
}