<?php namespace App\Repositories;

use App\Models\Historicals;
use Carbon\Carbon;

Class IndividualStockRepository implements IndividualStockRepositoryInterface{
	public function getGraphData($stockCode, $timeFrame = 'last_month'){
		$historicals = Historicals::where(['stock_code' => $stockCode])->dateCondition($timeFrame)->orderBy('date')->get();
		$graphData = array();
		foreach($historicals as $record){
			array_push($graphData, array($this->makeCarbonDate($record->date)->toFormattedDateString(), $record->close));
		}
		return $graphData;
	}

	private function makeCarbonDate($date){
		$datePieces = explode('-', $date);
		return Carbon::createFromDate($datePieces[0], $datePieces[1], $datePieces[2]);
	}
}