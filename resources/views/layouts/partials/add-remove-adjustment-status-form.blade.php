{!! Form::open(["method" => "post", "action" => ["DashboardController@changeStockAdjustmentStatus", $stockCode]]) !!}
    {!! Form::hidden("adjustment", $adjustmentValue) !!}
    {!! Form::hidden("stockCode", $stockCode) !!}
    {!! Form::button("", ["name" => "removeFromList", "type" => "submit", "class" => "glyphicon glyphicon-remove center-block", "aria-hidden" => "true"]) !!}
{!! Form::close() !!}