@extends('layouts.master')

@section('title')
	Screener
@stop

@section('body')
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default">
		 	<div class="panel-heading">
		        <th colspan="2">Enter the metrics you'd like to search for</th>
		  	</div>
			
				{!! Form::open(array('action' => 'SearchController@show', 'method' => 'GET'), ['class' => 'form-inline']) !!}
				<table class="table">
					<tr>
						<div class="form-group">
							<td align="right"><b>Sector</b></td>
							<td colspan="4">{!! Form::select('stockSector', $sectors, 0, ['class' => 'form-control']) !!}</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'stockSector'])
					<tr>
						<div class="form-group">
							<td><b>Stock Price</b></td>
							<td><label for="minPrice">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'minPrice', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="maxPrice">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'maxPrice', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minPrice'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxPrice'])
					<tr>
						<div class="form-group">
							<td><b>Average Daily Volume</b></td>
							<td><label for="minVolume">Min:</label></td>
							<td>{!! Form::input('text', 'minVolume', null, ['class' => 'form-control', 'placeholder' => '1000']) !!}</td>
							<td><label for="maxVolume">Max:</label></td>
							<td>{!! Form::input('text', 'maxVolume', null, ['class' => 'form-control', 'placeholder' => '100000']) !!}</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minVolume'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxVolume'])
					<tr>
						<div class="form-group">
							<td><b>EBITDA</b></td>
							<td><label for="minEBITDA">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'minEBITDA', null, ['class' => 'form-control', 'placeholder' => '1000']) !!}
								</div>
							</td>
							<td><label for="maxEBITDA">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'maxEBITDA', null, ['class' => 'form-control', 'placeholder' => '1000']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minEBITDA'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxEBITDA'])
					<tr>
						<div class="form-group">
							<td><b>EPS Current Year</b></td>
							<td><label for="minEPSCurrentYear">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'minEPSCurrentYear', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="maxEPSCurrentYear">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'maxEPSCurrentYear', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minEPSCurrentYear'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxEPSCurrentYear'])
					<tr>
						<div class="form-group">
							<td><b>EPS Next Year</b></td>
							<td><label for="minEPSNextYear">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'minEPSNextYear', null, ['class' => 'form-control', 'placeholder' => '0.00', 'disabled']) !!}
								</div>
							</td>
							<td><label for="maxEPSNextYear">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'maxEPSNextYear', null, ['class' => 'form-control', 'placeholder' => '0.00', 'disabled']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minEPSNextYear'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxEPSNextYear'])
					<tr>
						<div class="form-group">
							<td><b>P/E Ratio</b></td>
							<td><label for="minPERatio">Min:</label></td>
							<td>{!! Form::input('text', 'minPERatio', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}</td>
							<td><label for="maxPERatio">Max:</label></td>
							<td>{!! Form::input('text', 'maxPERatio', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minPERatio'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxPERatio'])
					<tr>
						<div class="form-group">
							<td><b>Price/Book</b></td>
							<td><label for="minPriceBook">Min:</label></td>
							<td>{!! Form::input('text', 'minPriceBook', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}</td>
							<td><label for="maxPriceBook">Max:</label></td>
							<td>{!! Form::input('text', 'maxPriceBook', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minPriceBook'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxPriceBook'])
					<tr>
						<div class="form-group">
							<td><b>52 Week High</b></td>
							<td><label for="min52WeekHigh">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'min52WeekHigh', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="max52WeekHigh">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'max52WeekHigh', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'min52WeekHigh'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'max52WeekHigh'])
					<tr>
						<div class="form-group">
							<td><b>52 Week Low</b></td>
							<td><label for="min52WeekLow">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'min52WeekLow', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="max52WeekLow">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'max52WeekLow', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'min52WeekLow'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'max52WeekLow'])
					<tr>
						<div class="form-group">
							<td><b>50 Day MA</b></td>
							<td><label for="min50DayMA">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'min50DayMA', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="max50DayMA">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'max50DayMA', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'min50DayMA'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'max50DayMA'])
					<tr>
						<div class="form-group">
							<td><b>200 Day MA</b></td>
							<td><label for="min200DayMA">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'min200DayMA', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
							<td><label for="max200DayMA">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">$</div>
									{!! Form::input('text', 'max200DayMA', null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
								</div>
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'min200DayMA'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'max200DayMA'])
					<tr>
						<div class="form-group">
							<td><b>Market Cap (M)</b></td>
							<td><label for="minMarketCap">Min:</label></td>
							<td>{!! Form::input('text', 'minMarketCap', null, ['class' => 'form-control', 'placeholder' => '0']) !!}</td>
							<td><label for="maxMarketCap">Max:</label></td>
							<td>{!! Form::input('text', 'maxMarketCap', null, ['class' => 'form-control', 'placeholder' => '0']) !!}</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minMarketCap'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxMarketCap'])
					<tr>
						<div class="form-group">
							<td><b>Dividend Yield</b></td>
							<td><label for="minDividendYield">Min:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">%</div>
										{!! Form::input('text', 'minDividendYield', null, ['class' => 'form-control', 'placeholder' => '0.00', 'disabled']) !!}
								</div>
							</td>
							<td><label for="maxDividendYield">Max:</label></td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">%</div>
									{!! Form::input('text', 'maxDividendYield', null, ['class' => 'form-control', 'placeholder' => '0.00', 'disabled']) !!}
								</div>
							</td>
						</div>
					</tr>
					<tr>
						<div class="form-group">
							<td align="right" colspan="4"><b>Show stocks with low quality or incomplete metrics</b></td>
							<td>
								{!! Form::checkbox('omitCondition', null, false, ['class' => 'quater-margin-left']) !!}
							</td>
						</div>
					</tr>
					@include('layouts.partials.form-error-row', ['fieldName' => 'minDividendYield'])
					@include('layouts.partials.form-error-row', ['fieldName' => 'maxDividendYield'])
					<tr>
						<td colspan="5">{!! Form::submit("Search", ['class' => 'btn btn-default form-control']) !!}</td>
					</tr>
				</table>
				{!! Form::close() !!}
		</div>
	</div>
@stop