<?php namespace App\Repositories;

interface SearchRepositoryInterface{
	public function getAllMetrics($omitCondition);
	public function getMetricsByStockList($listOfStocks, $omitCondition);
	public function getSearchResults($request);
}