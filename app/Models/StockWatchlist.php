<?php namespace App\Models;

use App\Models\StockWatchlist;
use App\Models\StockMetrics;
use Illuminate\Database\Eloquent\Model;

class StockWatchlist extends Model
{
    protected $table = 'stocks_watchlist';

    public static function getStockMetricsDataForPortfolio($watchlistId){
    	$stocksInWatchlist = StockWatchlist::where('watchlist_id', $watchlistId)->lists('stock_code');
    	return StockMetrics::whereIn('stock_code', $stocksInWatchlist)->with('stock')->get();
    }
}
