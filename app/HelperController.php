<?php

namespace App;

class HelperController {
	public static function dateFormat($date, $format_to_change_to, $format_to_change_from='Y-m-d H:i:s') {
		try {
			$date = \Carbon\Carbon::createFromFormat($format_to_change_from, $date)->format($format_to_change_to);
		} catch(\Exception $e) {

		}

		return $date;
	}
	public static function convertIntegerToDecimal($number) {
		if(is_null($number)) {
			return $number;
		}
		return $number / 100;
	}

	public static function convertDecimalToInteger($number) {
			if(is_null($number)) {
				return $number;
			}
			try {
				return ((int) ($number * 100));		
			} 
			catch(\Exception $e) {
				return $number;
			}
		
	}

	public static function getTwoDecimalValue($number) {
		$number = self::convertDecimalToInteger($number);
		$number = self::convertIntegerToDecimal($number);

		return $number;
	}
}
