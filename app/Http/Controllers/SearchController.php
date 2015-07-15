<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ScreenerSearchRequest;
use App\Http\Controllers\Controller;
use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Repositories\SearchRepositoryInterface;
use Illuminate\Http\Request;

use Datatables;

class SearchController extends Controller {

	protected $search;

	public function __construct(SearchRepositoryInterface $search){
		$this->search = $search;
	}
	
	public function index(){
		return view('pages.screener')->with([
			'sectors' => Stock::getSectorDropdown()
		]);
	}

	public function show(ScreenerSearchRequest $request){
		$stocks = StockMetrics::getMetricsByStockList($this->search->getSearchResults($request), $request->omitCondition);
		if($request->viewType == 'partial'){
			if($request->section == 'sectorDayChange'){
				return view('layouts.partials.sector-day-gains-display')->with(['sectorDayChanges' => SectorHistoricals::getSectorDayChanges()]);
			}
			return view('layouts.partials.stock-list-display')->with(['stocks' => $stocks]);
		}
		return view('pages.stocks')->with([
			'stocks' => $stocks,
			'sectorDayChanges' => SectorHistoricals::getSectorDayChanges(),
			'stockSectors' => Stock::getSectorDropdown(),
			'stockSectorName' => $request->stockSector
		]);
	}

	public function marketChange(){
		return view('layouts.partials.market-change-display')
			->with(['marketChange' => SectorHistoricals::getMarketChange()]);
	}
}
