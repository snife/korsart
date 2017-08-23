<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 23.08.2017
 * Time: 12:13
 */

function formatDate($date) {
	$month = (int)substr($date, 5, 2);
	$year = (int)substr($date, 0, 4);

	switch ($month) {
		case 1:
			$date = "Январь";
			break;
		case 2:
			$date = "Февраль";
			break;
		case 3:
			$date = "Март";
			break;
		case 4:
			$date = "Апрель";
			break;
		case 5:
			$date = "Май";
			break;
		case 6:
			$date = "Июнь";
			break;
		case 7:
			$date = "Июль";
			break;
		case 8:
			$date = "Август";
			break;
		case 9:
			$date = "Сентябрь";
			break;
		case 10:
			$date = "Октябрь";
			break;
		case 11:
			$date = "Ноябрь";
			break;
		case 12:
			$date = "Декабрь";
			break;
		default:
			break;
	}

	$date .= ", ".$year;

	return $date;
}