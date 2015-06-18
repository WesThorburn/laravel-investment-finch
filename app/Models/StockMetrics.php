<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMetrics extends Model {

	protected $table = 'stock_metrics';

	public function stock(){
		return $this->belongsTo('App\Models\Stock', 'stock_code', 'stock_code');
	}

	public function scopeOmitOutliers($query, $omitCondition = 'omit'){
		if($omitCondition == 'omit'){
			return $query->where('last_trade', '>=', '0.05')
				->where('average_daily_volume', '!=', 0)
				->where('earnings_per_share_current', '>=', 0.01)
				->where('price_to_earnings', '>=', 0.01)
				->where('price_to_book', '>=', 0.01)
				->where('market_cap', '!=', 'N/A');
		}
		return $query;
		
	}
}
