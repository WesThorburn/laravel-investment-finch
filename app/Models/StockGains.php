<?php namespace App\Models;

use App\Models\StockGains;
use Illuminate\Database\Eloquent\Model;

class StockGains extends Model
{
    protected $table = 'stock_gains';

    protected $guarded = ['id','created_at'];

    public function stock(){
        return $this->belongsTo('App\Models\Stock', 'stock_code', 'stock_code');
    }

    public static function getTopStocksThisWeek($stockList, $limit = 13){
        return StockGains::whereIn('stock_code', $stockList)->orderBy('week_change', 'desc')->take($limit)->get();
    }

    public static function getBottomStocksThisWeek($stockList, $limit = 13){
        return StockGains::whereIn('stock_code', $stockList)->orderBy('week_change', 'asc')->take($limit)->get();
    }

    public static function getTopStocksThisMonth($stockList, $limit = 13){
        return StockGains::whereIn('stock_code', $stockList)->orderBy('month_change', 'desc')->take($limit)->get();
    }

    public static function getBottomStocksThisMonth($stockList, $limit = 13){
        return StockGains::whereIn('stock_code', $stockList)->orderBy('month_change', 'asc')->take($limit)->get();
    }

    public static function getTopStocks12Months($limit = 18){
        $stockList = StockMetrics::omitOutliers()->where('current_market_cap', '>=', 100)->lists('stock_code');
        return StockGains::whereIn('stock_code', $stockList)->where('year_change', '<', 250)->orderBy('year_change', 'desc')->take($limit)->get();
    }
}
