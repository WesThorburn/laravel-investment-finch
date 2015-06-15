<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ScreenerSearchRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'sector' => 'required',
			'minPrice' => 'numeric',
			'maxPrice' => 'numeric',
			'minVolume' => 'numeric',
			'maxVolume' => 'numeric',
			'minEBITDA' => 'numeric',
			'maxEBITDA' => 'numeric',
			'minEPSCurrentYear' => 'numeric',
			'maxEPSCurrentYear' => 'numeric',
			'minEPSNextYear' => 'numeric',
			'maxEPSNextYear' => 'numeric',
			'minPERatio' => 'numeric',
			'maxPERatio' => 'numeric',
			'minPriceBook' => 'numeric',
			'maxPriceBook' => 'numeric',
			'min52WeekHigh' => 'numeric',
			'max52WeekHigh' => 'numeric',
			'min52WeekLow' => 'numeric',
			'max52WeekLow' => 'numeric',
			'min50DayMA' => 'numeric',
			'max50DayMA' => 'numeric',
			'min200DayMA' => 'numeric',
			'max200DayMA' => 'numeric',
			'minMarketCap' => 'numeric',
			'maxMarketCap' => 'numeric',
			'minDividendYield' => 'numeric',
			'maxDividendYield' => 'numeric'
		];
	}

}
