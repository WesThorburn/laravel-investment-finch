<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SectorHistoricals;
use App\Models\Stock;
use Khill\Lavacharts\Lavacharts;

class GraphController extends Controller
{
    public function stock($stockCode, $timeFrame, $dataType){
        $graphData = Stock::getGraphData($stockCode, $timeFrame, $dataType);
        return $this->graph($graphData, $dataType);
    }

    public function sector($sectorName, $timeFrame, $dataType){
        $graphData = SectorHistoricals::getGraphData($sectorName, $timeFrame, $dataType);
        return $this->graph($graphData, $dataType);
    }

    public function graph($data, $dataType){
        $prices = \Lava::DataTable();
        $prices->addStringColumn('Date')
            ->addNumberColumn($dataType)
            ->addRows($data);
        return $prices->toJson();
    }
}
