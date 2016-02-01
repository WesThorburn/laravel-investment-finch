<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\SearchRequest;
use App\Http\Controllers\Controller;
use App\Models\StockGains;
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
}
