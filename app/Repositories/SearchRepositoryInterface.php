<?php namespace App\Repositories;

interface SearchRepositoryInterface{
	public function getAllMetrics($omitCondition);
	public function getSearchResults($request);
}