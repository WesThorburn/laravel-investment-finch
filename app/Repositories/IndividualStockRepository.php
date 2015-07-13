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
			array_push($graphData, array($this->makeCarbonDate($record->date)->toFormattedDateString(), $recordValue));
		}
		return $graphData;
	}

	public function getRelatedStocks($stockCode){
		$otherStocksInSector = Stock::where('sector', Stock::where('stock_code', $stockCode)->pluck('sector'))->lists('stock_code');
		return $otherStocksInSector;
	}

	private function makeCarbonDate($date){
		$datePieces = explode('-', $date);
		return Carbon::createFromDate($datePieces[0], $datePieces[1], $datePieces[2]);
	}
}