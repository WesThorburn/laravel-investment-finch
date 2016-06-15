<?php namespace App\Http\Controllers;

use Response;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller {
	public function autocomplete(){
		$stocks = Stock::join('stock_metrics', 'stocks.stock_code', '=', 'stock_metrics.stock_code')
			->select('stocks.stock_code', 'stocks.company_name')
			->where('stocks.stock_code', 'LIKE', '%'.Input::get('term').'%')
			->orWhere('stocks.company_name', 'LIKE', '%'.Input::get('term').'%')
			->where('stocks.deleted_at', '=', null)
			->orderBy('stock_metrics.current_market_cap', 'DESC')
			->take(5)
			->get();

		$searchResults = [];
		foreach($stocks as $stock){
			$searchResults[] = ['id' => $stock->stock_code, 'value' => $stock->stock_code . '  -  ' .$stock->company_name];
		}
		return Response::json($searchResults);
	}
}
