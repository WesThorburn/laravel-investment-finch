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
			'sectors' => Stock::getSectorDropdown(), 
			'sectorName' => null
		]);
	}
	
	public function index(){
		return view('pages.screener')->with([
			'sectors' => Stock::getSectorDropdown()
		]);
	}

	public function show(ScreenerSearchRequest $request){
		return view('pages.stocks')->with([
			'stocks' => $this->search->getMetricsByStockList($this->search->getSearchResults($request), $request->omitCondition), 
			'sectors' => Stock::getSectorDropdown(), 
			'sectorName' => $request->sector
		]);
	}
}
