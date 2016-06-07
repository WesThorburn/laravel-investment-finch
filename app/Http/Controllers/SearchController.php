<?php namespace App\Http\Controllers;

use Response;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller {
	public function autocomplete(){
		$stocks = Stock::select('stock_code', 'company_name')
			->where('stock_code', 'LIKE', '%'.Input::get('term').'%')
			->orWhere('company_name', 'LIKE', '%'.Input::get('term').'%')
			->take(5)
			->get();

		$searchResults = [];
		foreach($stocks as $stock){
			$searchResults[] = ['id' => $stock->stock_code . ' ' .$stock->company_name, 'value' => $stock->stock_code . ' - ' .$stock->company_name];
		}
		return Response::json($searchResults);
	}
}
