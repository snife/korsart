<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 13:41
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);
$title = $mysqli->real_escape_string($_POST['title']);
$keywords = $mysqli->real_escape_string($_POST['keywords']);
$description = $mysqli->real_escape_string($_POST['description']);
$text = $mysqli->real_escape_string($_POST['text']);

if(substr($keywords, strlen($keywords) - 1, 1) == ",") {
	$keywords = substr($keywords, 0, strlen($keywords) - 1);
}

if($mysqli->query("UPDATE pages SET title = '".$title."', keywords = '".$keywords."', description = '".$description."' WHERE id = '".$id."'")) {
	if($id == 1) {
		$mysqli->query("UPDATE text SET text = '".nl2br($text)."' WHERE id = 1");
	}

	echo "ok";
} else {
	echo "failed";
}