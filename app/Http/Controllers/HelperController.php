<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
	public static function addJsFile($js_type, $url, $type=NULL, $integrity=NULL, $cross_origin=NULL) {
		$session_js = session()->get($js_type);
		$session_js = !is_null($session_js) ? $session_js : [];
		$type = !is_null($type) ? 'type="'.$type.'"' : '';
		$integrity = !is_null($integrity) ? 'integrity="'.$integrity.'"' : '';
		$cross_origin = !is_null($cross_origin) ? 'crossorigin="'.$cross_origin.'"' : '';
		$session_js[] = '<script src="'.$url.'" '.$type.' '.$integrity.' '.$cross_origin.'></script>';
		$session_js = array_unique($session_js);
		session()->put($js_type, $session_js);
	}

	public static function displayJsFile($js_type) {
		$js = session()->get($js_type);
		session()->forget($js_type);
		$js = !is_null($js) ? $js : [];
		$html = '';
		foreach($js as $j) {
			$html .= $j;
		}
			
		return $html;
	}

	public static function noOfDecimals($number, $decimals = 2) {
		return  (int) ($number * pow(10, $decimals)) / pow(10, $decimals);
	}

	public static function makeAssociativeArray($keys = [], $data) {
		$return_data = [];
		foreach($data as $d) {
			foreach($keys as $k) {
			
			}
		}
	}

	public static function antDTableFormatter($data) {
		

		$headers = ['row_style' => '', 'columns' => []];
		$rows = [];
		foreach($data['headers']['data'] as $column_number => $h) {
			// $headers[] = [
			// 	"title" => $h,
			// 	"dataIndex" => 'col-'. $column_number,
			// 	"key" => 'col-'. $column_number
			// ];
			$headers['columns'][] = ['style' => '', 'data' => $h];
		}

		foreach($data['rows']['data'] as $row_number => $row) {
			// $temp = [];
			
			// 	$temp = ['key' => $row_number];
			// 	foreach($headers as $column_number => $h) {
			// 		$temp['col-'.$column_number] = $row[$column_number];
			// 	}
			// $rows[] = $temp;
			$temp = ['row_style' => self::mapStyleToAntDClass($data['rows']['style'][$row_number]), 'columns' => []];
			
			foreach($row as $column) {
				$temp['columns'][] = ['style' => '', 'data' => $column];
			}
			
			$rows[] = $temp;
		}

		return ['headers' => $headers, 'rows' => $rows];
	}

	public static function mapStyleToAntDClass($array_of_styles) {
		$return = [];
		$map = [
			'bold' => 'statement_table_bold',
		];

		foreach($array_of_styles as $style) {
			$return[] = isset($map[$style]) ? $map[$style] : '';
		}

		return implode(' ', $return);
	}
}