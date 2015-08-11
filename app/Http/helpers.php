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
		$marketClosedDays = ['2015-01-01','2015-01-26','2015-04-03','2015-04-06','2015-04-25','2015-06-08'];
		if(!in_array(date("Y-m-d"), $marketClosedDays)){
			return true;
		}
		return false;
	}
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