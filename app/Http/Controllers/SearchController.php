<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ScreenerSearchRequest;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Repositories\SearchRepositoryInterface;

use Illuminate\Http\Request;

class SearchController extends Controller {

	protected $search;

	public function __construct(SearchRepositoryInterface $search){
		$this->search = $search;
	}

	public function home($omitCondition = 'omit'){
		return view('pages.stocks')->with([
			'stocks' => $this->search->getAllMetrics($omitCondition), 
			'stockSectors' => Stock::getSectorDropdown(), 
			'stockSectorName' => null
		]);
	}
	
	public function index(){
		return view('pages.screener')->with([
			'sectors' => Stock::getSectorDropdown()
		]);
	}

	public function show(ScreenerSearchRequest $request){
		$stocks = $this->search->getMetricsByStockList($this->search->getSearchResults($request), $request->omitCondition);
		if($request->viewType == 'partial'){
			return view('layouts.partials.stock-list-display')->with(['stocks' => $stocks]);
		}
		return view('pages.stocks')->with([
			'stocks' => $stocks, 
			'stockSectors' => Stock::getSectorDropdown(),
			'stockSectorName' => $request->stockSector
		]);
	}
}
