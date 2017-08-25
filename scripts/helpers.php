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

function dateForComment($date) {
	$d = (int)substr($date, 8, 2)."-го ";

	switch((int)substr($date, 5, 2)) {
		case 1:
			$d .= "января";
			break;
		case 2:
			$d .= "февраля";
			break;
		case 3:
			$d .= "марта";
			break;
		case 4:
			$d .= "апреля";
			break;
		case 5:
			$d .= "мая";
			break;
		case 6:
			$d .= "июня";
			break;
		case 7:
			$d .= "июля";
			break;
		case 8:
			$d .= "августа";
			break;
		case 9:
			$d .= "сентября";
			break;
		case 10:
			$d .= "октября";
			break;
		case 11:
			$d .= "ноября";
			break;
		case 12:
			$d .= "декабря";
			break;
		default:
			break;
	}

	$d .= " ".substr($date, 0, 4)." г. в ".substr($date, 11);

	return $d;
}