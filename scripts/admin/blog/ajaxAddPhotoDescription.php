<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.09.2017
 * Time: 11:35
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));

if($mysqli->query("UPDATE blog_photos SET description = '".$description."' WHERE id = '".$id."'")) {
	echo "ok";
}