<?php

use Carbon\Carbon;

function getCurrentTimeIntVal(){
	date_default_timezone_set("Australia/Sydney");
    return intval(str_replace(':', '', date('H:i:s')));
}

function getMarketStatus(){
	if(getCurrentTimeIntVal() >= 100000 && getCurrentTimeIntVal() <= 160000){
		if(isTradingDay()){
			if(date("Y-m-d") == "2015-12-24" || date("Y-m-d") == "2015-12-31"){
				if(getCurrentTimeIntVal() <= 141000){
					return "Market Open";
				}
				else{
					return "Market Closed";
				}
			}
			else{
				return "Market Open";
			}
		}
	}
	return "Market Closed";
}

function isTradingDay(){
	if(Carbon::now()->isWeekDay()){
		$marketClosedDays = ['2015-01-01','2015-01-26','2015-04-03','2015-04-06','2015-04-25','2015-06-08','2015-12-25','2015-12-28'];
		if(!in_array(date("Y-m-d"), $marketClosedDays)){
			return true;
		}
		return false;
	}
}

function formatMoneyAmount($amount){
	if(!$amount){
		return "";
	}
	elseif($amount >= 1000){
		return ($amount/1000) . "B";
	}
	elseif($amount < 1000){
		return $amount . "M";
	}
}

function formatCompanyName($stockCode, $name){
	$reservedWords = ['Air', 'Pro'];

	$standardUcWords = ucwords(strtolower($name));
	$firstChar = substr($standardUcWords, 0, 1);
	$firstTwoChars = substr($standardUcWords, 0, 2);
	$firstThreeChars = substr($standardUcWords, 0, 3);
	$firstFourChars = substr($standardUcWords, 0, 4);

	if(strtoupper($stockCode).' ' == strtoupper($firstFourChars)){
		return strtoupper($firstFourChars).substr($standardUcWords, 3);
	}
	elseif(strtoupper(substr($stockCode,0,2)) == strtoupper($firstTwoChars) && !in_array($firstThreeChars, $reservedWords)){
		if(substr($standardUcWords, 3, 1) == ' ' || substr($standardUcWords, 2, 1) == ' '){
			return strtoupper($firstThreeChars).substr($standardUcWords, 3);
		}
	}
	elseif(strtoupper(substr($stockCode,0,1)) == $firstChar && !in_array($firstThreeChars, $reservedWords)){
		if(substr($standardUcWords, 3, 1) == ' ' || substr($standardUcWords, 2, 1) == ' '){
			return strtoupper($firstThreeChars).substr($standardUcWords, 3);
		}
	}
	return ucwords(strtolower($name));
}

function getServerTime(){
	return date('l F j, Y, g:i a')." (Sydney)";
}

function getDateFromCarbonDate($carbonInstance){
	//Receives a carbon instance and returns only the date (not time)
	return explode(' ', $carbonInstance)[0];
}

function getCarbonDateFromDate($phpDate){
	//Receives a date and returns it as a carbon instance
	$phpDateWithoutQuotes = str_replace('"', '', $phpDate);
	$explodedDate = explode('-', $phpDateWithoutQuotes);
	return Carbon::createFromDate($explodedDate[0], $explodedDate[1], $explodedDate[2]);
}