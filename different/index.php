<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 17:52
 */

include("../scripts/connect.php");

$categoryResult = $mysqli->query("SELECT * FROM categories WHERE sef_link = 'different'");
$category = $categoryResult->fetch_assoc();

if(!empty($category)) {
	$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$category['id']."' ORDER BY priority LIMIT 1");
	$subcategory = $subcategoryResult->fetch_assoc();

	if(!empty($subcategory)) {
		header("Location: ../".$subcategory['sef_link']);
	} else {
		header("Location: ../");
	}
} else {
	header("Location: ../");
}