<?php namespace App\Repositories;

use App\Models\Historicals;
use App\Models\StockMetrics;
use App\Models\Stock;
use Carbon\Carbon;

Class IndividualStockRepository implements IndividualStockRepositoryInterface{
	public function getGraphData($stockCode, $timeFrame = 'last_month', $dataType){
		$historicals = Historicals::where(['stock_code' => $stockCode])->dateCondition($timeFrame)->orderBy('date')->get();
		$graphData = array();
		foreach($historicals as $record){
			if($dataType == 'Price'){
				$recordValue = $record->close;
			}
			elseif($dataType == 'Volume'){
				$recordValue = $record->volume;
			}
			array_push($graphData, array(getCarbonDateFromDate($record->date)->toFormattedDateString(), $recordValue));
		}
		//Add Current day's trade value to graph data
		if(getMarketStatus() == "Market Open" && !Historicals::where(['stock_code' => $stockCode, 'date' => date('Y-m-d')])->first()){
			$stockMetric = StockMetrics::where('stock_code', $stockCode)->first();
			$metricDate = explode(" ", $stockMetric->updated_at)[0];
			array_push($graphData, array(getCarbonDateFromDate($metricDate)->toFormattedDateString(), $stockMetric->last_trade));
		}
		return $graphData;
	}
}