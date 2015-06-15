<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

	protected $table = 'stocks';

	public function stock(){
		return $this->hasOne('App\Models\StockMetrics', 'stock_code', 'stock_code');
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
