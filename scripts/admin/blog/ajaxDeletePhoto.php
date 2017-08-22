<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 10:22
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$photoResult = $mysqli->query("SELECT * FROM blog_photos WHERE id = '".$id."'");
$photo = $photoResult->fetch_assoc();

if($mysqli->query("DELETE FROM blog_photos WHERE id = '".$id."'")) {
	unlink("../../../img/photos/blog/content/".$photo['file']);

	echo "ok";
} else {
	echo "failed";
}