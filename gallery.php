<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 04.08.2017
 * Time: 10:42
 */

include("scripts/connect.php");

/* SEF-Links Processor */

$address = substr($_SERVER['REQUEST_URI'], 1);
$validity = 0;

$linkCheckResult = $mysqli->query("SELECT * FROM categories WHERE sef_link = '" . $address . "'");
$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

if ($linkCheck[0] > 0) {
	$validity = 1;
	$tableName = "categories";
} else {
	$linkCheckResult = $mysqli->query("SELECT * FROM subcategories WHERE sef_link = '" . $address . "'");
	$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

	if ($linkCheck[0] > 0) {
		$validity = 1;
		$tableName = "subcategories";
	} else {
		$linkCheckResult = $mysqli->query("SELECT * FROM pages WHERE sef_link = '" . $address . "'");
		$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

		if ($linkCheck[0] > 0) {
			$validity = 1;
			$tableName = "pages";
		}
	}
}

if ($validity == 1) {
	$linkResult = $mysqli->query("SELECT * FROM " . $tableName . " WHERE sef_link = '" . $address . "'");
	$link = $linkResult->fetch_assoc();

	print_r($link);
} else {
	header("Location: /");
}