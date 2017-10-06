<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 06.10.2017
 * Time: 10:40
 */

include("../scripts/connect.php");

$subcategoryResult = $mysqli->query("SELECT * FROM blog_subcategories ORDER BY priority LIMIT 1");
$subcategory = $subcategoryResult->fetch_assoc();

if(!empty($subcategory)) {
	header("Location: ../blog/".$subcategory['sef_link']);
} else {
	header("Location: ../");
}