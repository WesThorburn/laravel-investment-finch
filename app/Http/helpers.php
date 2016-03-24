<?php

use Carbon\Carbon;

function getCurrentTimeIntVal(){
	date_default_timezone_set("Australia/Sydney");
    return intval(str_replace(':', '', date('H:i:s')));
}

function isMarketOpen(){
	if(isTradingDay()){
		if(getCurrentTimeIntVal() >= 100000 && getCurrentTimeIntVal() <= 160000){
			//Allowance for days where market closes early. 
			if(date("Y-m-d") == "2016-12-23" || date("Y-m-d") == "2016-12-30"){
				if(getCurrentTimeIntVal() <= 141000){
					return true;
				}
				return false;
			}
			return true;
		}
		return false;
	}
	return false;
}

function isTradingDay(){
	if(Carbon::now()->isWeekDay()){
		$marketClosedDays = ['2016-03-25','2016-03-28','2016-04-25','2016-06-13','2016-12-26','2016-12-27'];
		if(!in_array(date("Y-m-d"), $marketClosedDays)){
			return true;
		}
		return false;
	}
}

function formatMoneyAmountToLetter($amount, $wordFormat = false){
	if(!$amount){
		return "";
	}
	elseif($amount >= 1000){
		if($wordFormat){
			return ($amount/1000) . " billion";
		}
		return ($amount/1000) . "B";
	}
	elseif($amount < 1000){
		if($wordFormat){
			return $amount . " million";
		}
		return $amount . "M";
	}
}

function formatMoneyAmountToNumber($amount){
	if(substr($amount, -1) == 'B'){
		return floatval(substr($amount, 0, -1))*1000;
	}
	elseif(substr($amount, -1) == 'M'){
		return floatval(substr($amount, 0, -1));
	}
}

function formatHundredThousandToMillion($amount){
	if($amount > 10000 || $amount < -10000){
		return $amount/1000000;
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

function getMonthNameFromNumber($monthNumber){
	$monthArray = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
	return $monthArray[intval($monthNumber)];
}