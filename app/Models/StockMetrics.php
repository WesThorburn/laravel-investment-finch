<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMetrics extends Model {

	protected $table = 'stock_metrics';

	protected $fillable = [
		"stock_code",
		"last_trade",
		"day_change",
		"average_daily_volume",
		"EBITDA",
		"earnings_per_share_current",
		"earnings_per_share_next_year",
		"price_to_earnings",
		"price_to_book",
		"year_high",
		"year_low",
		"fifty_day_moving_average",
		"two_hundred_day_moving_average",
		"market_cap",
		"dividend_yield",
		"updated_at"
	];

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
