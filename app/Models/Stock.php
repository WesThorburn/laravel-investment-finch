<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

	protected $table = 'stocks';

	protected $fillable = [
		'stock_code',
		'company_name',
		'sector'
	];

	public function metrics(){
		return $this->hasOne('App\Models\StockMetrics', 'stock_code', 'stock_code');
	}

	public function gains(){
		return $this->hasOne('App\Models\StockGains', 'stock_code', 'stock_code');
	}

	public static function getSectorDropdown(){
		$sectors = \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->get();
		$sectorDropdown = array("All" => "All");
		foreach($sectors as $key => $sector){
			$sectorDropdown[$sector->sector] = $sector->sector;
		}
		return $sectorDropdown;
	}
	
}
