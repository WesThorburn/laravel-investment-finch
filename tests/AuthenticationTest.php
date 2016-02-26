<?php

use App\Models\User;

class AuthenticationTest extends TestCase{
	public function testNotLoggedIn(){
		$this->visit('dashboard/discontinued')
			->seePageIs('/auth/login');
	}

	public function testFailedLogIn(){
		$this->visit('/auth/login')
			->press('Login')
			->see('Whoops!')
			->see('There were some problems with your input.');
	}

	public function testLoggedIn(){
		$user = User::where('email', 'wjthorburn@live.com.au')->first();
		$this->actingAs($user)
			->visit('dashboard/discontinued')
			->see('Discontinued Stocks')
			->click('Market Cap Adjustments')
			->see('Stocks with Adjusted Market Caps (/1000)');
	}

	public function testLogout(){
		$user = User::where('email', 'wjthorburn@live.com.au')->first();
		$this->actingAs($user)
			->visit('dashboard/discontinued')
			->seePageIs('dashboard/discontinued')
			->visit('auth/logout')
			->seePageIs('/');
	}
}