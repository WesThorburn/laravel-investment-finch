<?php namespace App\Models;

use App\Models\PortfolioStock;
use App\Models\Stock;

class PortfolioStock extends Stock
{
    protected $table = 'portfolio_stocks';

    public static function stockIsInPortfolio($stockCode, $portfolioId){
    	if(PortfolioStock::where(['stock_code' => $stockCode, 'portfolio_id' => $portfolioId])->first()){
    		return true;
    	}
    }
}
