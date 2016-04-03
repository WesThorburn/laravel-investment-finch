<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PortfolioStock;
use App\Models\Stock;

class PortfolioStock extends Model
{
    protected $table = 'portfolio_stocks';

    public function scopeWhereStockInPortfolio($query, $stockCode, $portfolioId){
    	return $query->where(['stock_code' => $stockCode, 'portfolio_id' => $portfolioId]);
    }

    public static function alreadyInPortfolio($stockCode, $portfolioId){
    	if(PortfolioStock::where(['stock_code' => $stockCode, 'portfolio_id' => $portfolioId])->first()){
    		return true;
    	}
    }

    public static function getStockMetricsDataForPortfolio($portfolioId){
    	return \DB::table('portfolio_stocks')
            ->join('stock_metrics', 'portfolio_stocks.stock_code', '=', 'stock_metrics.stock_code')
            ->select(
                'portfolio_stocks.portfolio_id', 
                'portfolio_stocks.stock_code', 
                'portfolio_stocks.purchase_price', 
                'portfolio_stocks.quantity',
                'stock_metrics.last_trade',
                'stock_metrics.day_change'
                )
            ->where('portfolio_stocks.portfolio_id', $portfolioId)
            ->get();
    }
}
