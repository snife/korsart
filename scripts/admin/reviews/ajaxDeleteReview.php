<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 8:39
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$reviewResult = $mysqli->query("SELECT * FROM reviews WHERE id = '".$id."'");
$review = $reviewResult->fetch_assoc();

if($mysqli->query("DELETE FROM reviews WHERE id = '".$id."'")) {
	unlink("../../../img/photos/reviews/".$review['photo']);
	echo "ok";
} else {
	echo "failed";
}