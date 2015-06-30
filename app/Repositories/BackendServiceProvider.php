<?php namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider{
	public function register(){
		$this->app->bind('App\Repositories\SearchRepositoryInterface','App\Repositories\SearchRepository');
		$this->app->bind('App\Repositories\IndividualStockRepositoryInterface','App\Repositories\IndividualStockRepository');
	}
}