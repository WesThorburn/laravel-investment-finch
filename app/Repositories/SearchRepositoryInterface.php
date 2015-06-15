<?php namespace App\Repositories;

interface SearchRepositoryInterface{
	public function getAllMetrics();
	public function getMetricsByStockList($listOfStocks);
	public function getScreenerResults($request);
}