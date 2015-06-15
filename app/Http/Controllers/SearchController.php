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

	public function home(){
		return view('pages.stocks')->with([
			'stocks' => $this->search->getAllMetrics(), 
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
		if($request->searchType == "sectorOnly"){
			if($request->sector == "All"){
				return $this->home();
			}
			$stockCodes = Stock::where('sector', $request->sector)->lists('stock_code');
		}
		elseif($request->searchType == "screener"){
			$stockCodes = $this->search->getScreenerResults($request);
		}
		return view('pages.stocks')->with([
			'stocks' => $this->search->getMetricsByStockList($stockCodes), 
			'sectors' => Stock::getSectorDropdown(), 
			'sectorName' => $request->sector
		]);
	}
}
