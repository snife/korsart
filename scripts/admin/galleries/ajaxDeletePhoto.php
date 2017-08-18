<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 10:27
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$photoResult = $mysqli->query("SELECT * FROM photos WHERE id = '".$id."'");
if($photoResult->num_rows == 1) {
	$photo = $photoResult->fetch_assoc();

	if($mysqli->query("DELETE FROM photos WHERE id = '".$id."'")) {
		unlink("../../../img/photos/gallery/content/".$photo['file']);
		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "id";
}